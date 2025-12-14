<?php
include "../config/koneksi.php";
include "header.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan'); window.location='pesanan.php';</script>";
    exit;
}

$id = $_GET['id'];
$id_user = $_SESSION['user']['id_212238'];

// Ambil data pesanan
$query = $koneksi->query("SELECT * FROM pesanan_212238 WHERE id_212238 = '$id' AND id_user_212238 = '$id_user'");
$data = $query->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='pesanan.php';</script>";
    exit;
}

$id_barang = $data['id_barang_212238'];
$jenis = $data['jenis_212238'];

// Ambil harga dari tabel sesuai jenis barang
if ($jenis == 'aksesoris') {
    $barang = $koneksi->query("SELECT harga_212238 FROM aksesoris_212238 WHERE id_212238 = '$id_barang'")->fetch_assoc();
} elseif ($jenis == 'pakan') {
    $barang = $koneksi->query("SELECT harga_212238 FROM pakan_212238 WHERE id_212238 = '$id_barang'")->fetch_assoc();
} else {
    echo "<script>alert('Jenis barang tidak valid'); window.location='pesanan.php';</script>";
    exit;
}

if (!$barang) {
    echo "<script>alert('Barang tidak ditemukan'); window.location='pesanan.php';</script>";
    exit;
}

$harga_satuan = $barang['harga_212238'];

// Handle submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jumlah         = $_POST['jumlah'];
    $alamat         = $_POST['alamat'];
    $catatan        = $_POST['catatan'];
    $metode_bayar   = $_POST['metode_bayar'];

    $total_baru     = $harga_satuan * $jumlah;

    $update = $koneksi->query("UPDATE pesanan_212238 SET
        jumlah_212238 = '$jumlah',
        alamat_212238 = '$alamat',
        catatan_212238 = '$catatan',
        metode_bayar_212238 = '$metode_bayar',
        total_harga_212238 = '$total_baru'
        WHERE id_212238 = '$id' AND id_user_212238 = '$id_user'
    ");

    if ($update) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Pesanan kamu telah diperbarui.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location = 'pesanan.php';
            });
        });
    </script>";
}
 else {
        echo "<script>alert('Gagal memperbarui pesanan');</script>";
    }
}
?>

<div class="container py-5">
  <h3 class="text-center text-maroon fw-bold mb-4">Edit Pesanan <?= ucfirst($data['jenis_212238']) ?></h3>
  <form method="POST">
    <div class="mb-3">
      <label for="jumlah" class="form-label">Jumlah</label>
      <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" value="<?= $data['jumlah_212238'] ?>" required>
    </div>

    <div class="mb-3">
      <label for="alamat" class="form-label">Alamat Pengiriman</label>
      <textarea name="alamat" id="alamat" class="form-control" rows="3" required><?= htmlspecialchars($data['alamat_212238']) ?></textarea>
    </div>

    <div class="mb-3">
      <label for="catatan" class="form-label">Catatan Tambahan (Opsional)</label>
      <textarea name="catatan" id="catatan" class="form-control" rows="3"><?= htmlspecialchars($data['catatan_212238']) ?></textarea>
    </div>

    <div class="mb-3">
      <label for="metode_bayar" class="form-label">Metode Pembayaran</label>
      <select name="metode_bayar" id="metode_bayar" class="form-control" required>
        <option value="cod" <?= $data['metode_bayar_212238'] == 'cod' ? 'selected' : '' ?>>COD</option>
        <option value="transfer" <?= $data['metode_bayar_212238'] == 'transfer' ? 'selected' : '' ?>>Transfer</option>
        <option value="qris" <?= $data['metode_bayar_212238'] == 'qris' ? 'selected' : '' ?>>QRIS</option>
      </select>
    </div>

    <button type="submit" class="btn btn-maroon">Simpan Perubahan</button>
    <a href="pesanan.php" class="btn btn-secondary">Batal</a>
  </form>
</div>

<style>
.text-maroon { color: #800000; }
.btn-maroon {
  background-color: #800000;
  color: #fff;
}
.btn-maroon:hover {
  background-color: #a83232;
}
</style>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
