<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../config/koneksi.php";
include "header.php";

// Cek user login
$isUserLoggedIn = isset($_SESSION['user']) && $_SESSION['user']['role_212238'] === 'user';
$id_user = $isUserLoggedIn ? $_SESSION['user']['id_212238'] : null;

// Proses pemesanan via AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_barang'], $_POST['jumlah'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah = intval($_POST['jumlah']);
    $alamat = $koneksi->real_escape_string($_POST['alamat']);
    $catatan = $koneksi->real_escape_string($_POST['catatan']);
    $metode = $koneksi->real_escape_string($_POST['metode']);
    $tgl = date("Y-m-d");
    $waktu = date("Y-m-d H:i:s");
    $id_pesanan = uniqid();

    $q = $koneksi->query("SELECT harga_212238, stok_212238 FROM pakan_212238 WHERE id_212238 = '$id_barang'");
    $dataBarang = $q->fetch_assoc();

    if ($dataBarang) {
        $harga = $dataBarang['harga_212238'];
        $stok = $dataBarang['stok_212238'];

        if ($jumlah > 0 && $stok >= $jumlah) {
            $total = $harga * $jumlah;

            // Simpan ke database
            $koneksi->query("INSERT INTO pesanan_212238 (
                id_212238,
                id_user_212238,
                jenis_212238,
                id_barang_212238,
                jumlah_212238,
                tgl_212238,
                status_bayar_212238,
                status_pesanan_212238,
                alamat_212238,
                catatan_212238,
                metode_bayar_212238,
                total_harga_212238,
                waktu_input_212238
            ) VALUES (
                '$id_pesanan',
                '$id_user',
                'pakan',
                '$id_barang',
                '$jumlah',
                '$tgl',
                'belum',
                'pending',
                '$alamat',
                '$catatan',
                '$metode',
                '$total',
                '$waktu'
            )");

            // Kurangi stok
            $koneksi->query("UPDATE pakan_212238 SET stok_212238 = stok_212238 - $jumlah WHERE id_212238 = '$id_barang'");

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
    exit;
}

// Pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$query = "SELECT * FROM pakan_212238";
if (!empty($search)) {
    $s = $koneksi->real_escape_string($search);
    $query .= " WHERE nama_pakan_212238 LIKE '%$s%' OR deskripsi_212238 LIKE '%$s%'";
}
$query .= " ORDER BY nama_pakan_212238 ASC";
$result = $koneksi->query($query);
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-5">
    <h2 class="text-center text-maroon fw-bold mb-4">Koleksi pakan Hewan</h2>

    <form method="get" class="mb-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="input-group shadow-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari pakan..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-maroon" type="submit">Cari</button>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($data = $result->fetch_assoc()): ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow h-100 product-card">
                        <img src="../assets/pakan/<?= htmlspecialchars($data['foto_212238']) ?>"
                            class="card-img-top"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title text-maroon"><?= htmlspecialchars($data['nama_pakan_212238']) ?></h5>
                            <p class="card-text" style="font-size:14px;color:#555;"><?= nl2br(htmlspecialchars($data['deskripsi_212238'])) ?></p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-danger">Rp <?= number_format($data['harga_212238'],0,',','.') ?></span>
                                <span class="badge bg-success">Stok: <?= $data['stok_212238'] ?></span>
                            </div>
                            <button type="button" class="btn btn-maroon w-100"
                                onclick="<?= $isUserLoggedIn ? "showOrderPopup('{$data['id_212238']}', '{$data['nama_pakan_212238']}', {$data['harga_212238']})" : "showLoginPopup()" ?>"
                                <?= $data['stok_212238'] == 0 ? 'disabled' : '' ?>>
                                <?= $data['stok_212238'] == 0 ? 'Stok Habis' : 'Pesan Sekarang' ?>
                            </button>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">Tidak ada data pakan ditemukan.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function showLoginPopup() {
    Swal.fire({
        title: 'Harap Login',
        text: 'Anda harus login untuk memesan.',
        icon: 'info',
        confirmButtonColor: '#800000',
        confirmButtonText: 'Login Sekarang',
        showCancelButton: true,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../login.php';
        }
    });
}

function showOrderPopup(id_barang, nama_barang, harga) {
    Swal.fire({
        title: 'Pesan: ' + nama_barang,
        html: `
            <input type="number" id="jumlah" class="swal2-input" placeholder="Jumlah" min="1">
            <input type="text" id="alamat" class="swal2-input" placeholder="Alamat pengiriman">
            <input type="text" id="catatan" class="swal2-input" placeholder="Catatan (opsional)">
            <select id="metode" class="swal2-input">
                <option value="cod">Bayar di Tempat</option>
                <option value="transfer">Transfer Bank</option>
                <option value="qris">QRIS</option>
            </select>
            <div id="totalHarga" style="margin-top:10px;font-weight:bold;color:#800000;"></div>
        `,
        didOpen: () => {
            const jumlahInput = document.getElementById('jumlah');
            const totalDiv = document.getElementById('totalHarga');
            jumlahInput.addEventListener('input', () => {
                const jumlah = parseInt(jumlahInput.value) || 0;
                const total = jumlah * harga;
                totalDiv.innerHTML = 'Total Harga: Rp ' + total.toLocaleString('id-ID');
            });
        },
        confirmButtonText: 'Pesan Sekarang',
        confirmButtonColor: '#800000',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const jumlah = document.getElementById('jumlah').value;
            const alamat = document.getElementById('alamat').value;
            const catatan = document.getElementById('catatan').value;
            const metode = document.getElementById('metode').value;

            if (!jumlah || jumlah <= 0 || !alamat) {
                Swal.showValidationMessage('Jumlah dan alamat wajib diisi');
            }

            return { jumlah, alamat, catatan, metode };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { jumlah, alamat, catatan, metode } = result.value;
            fetch("", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `id_barang=${id_barang}&jumlah=${jumlah}&alamat=${encodeURIComponent(alamat)}&catatan=${encodeURIComponent(catatan)}&metode=${metode}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', 'Pesanan berhasil dibuat.', 'success')
                        .then(() => window.location.reload());
                } else {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                }
            });
        }
    });
}

</script>

<!-- Tambahan Style -->
<style>
.text-maroon { color: #800000; }
.btn-maroon {
    background-color: #800000;
    color: #fff;
}
.btn-maroon:hover {
    background-color: #a83232;
}
.product-card {
    transition: transform 0.3s ease;
}
.product-card:hover {
    transform: scale(1.02);
}
</style>
