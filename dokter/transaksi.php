<?php
include '../config/koneksi.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : '';
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';

$wherePesanan = "WHERE p.status_bayar_212238 = 'sudah'";
$wherePemeriksaan = "WHERE pr.status_bayar_212238 = 'sudah'";

if (!empty($tgl_awal) && !empty($tgl_akhir)) {
    $wherePesanan .= " AND p.tgl_212238 BETWEEN '$tgl_awal' AND '$tgl_akhir'";
    $wherePemeriksaan .= " AND pr.tgl_212238 BETWEEN '$tgl_awal' AND '$tgl_akhir'";
}

$transaksi = [];

// Query pesanan
$query_pesanan = mysqli_query($koneksi, "
    SELECT 
        p.id_212238,
        u.nama_212238 AS nama_user,
        'Pesanan Aksesoris/Pakan' AS jenis_transaksi,
        p.total_harga_212238 AS total,
        p.metode_bayar_212238 AS metode_bayar,
        p.tgl_212238 AS tanggal,
        p.status_bayar_212238 AS status_bayar
    FROM pesanan_212238 p
    JOIN users_212238 u ON p.id_user_212238 = u.id_212238
    $wherePesanan
");

if ($query_pesanan) {
    while ($row = mysqli_fetch_assoc($query_pesanan)) {
        $transaksi[] = $row;
    }
} else {
    echo "<div class='alert alert-danger'>Gagal mengambil data pesanan: " . mysqli_error($koneksi) . "</div>";
}

// Query pemeriksaan
$query_pemeriksaan = mysqli_query($koneksi, "
    SELECT 
        pr.id_212238,
        u.nama_212238 AS nama_user,
        'Pemeriksaan' AS jenis_transaksi,
        pr.total_212238 AS total,
        pr.metode_bayar_212238 AS metode_bayar,
        pr.tgl_212238 AS tanggal,
        pr.status_bayar_212238 AS status_bayar
    FROM pemeriksaan_212238 pr
    JOIN users_212238 u ON pr.id_user_212238 = u.id_212238
    $wherePemeriksaan
");

if ($query_pemeriksaan) {
    while ($row = mysqli_fetch_assoc($query_pemeriksaan)) {
        $transaksi[] = $row;
    }
} else {
    echo "<div class='alert alert-danger'>Gagal mengambil data pemeriksaan: " . mysqli_error($koneksi) . "</div>";
}

// Urutkan berdasarkan tanggal DESC
usort($transaksi, function ($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});
?>

<div class="container mt-5">
    <h3 class="mb-4 text-danger">Laporan Transaksi (Sudah Dibayar)</h3>

    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Dari Tanggal</label>
            <input type="date" name="tgl_awal" class="form-control" value="<?= htmlspecialchars($tgl_awal) ?>">
        </div>
        <div class="col-md-4">
            <label>Sampai Tanggal</label>
            <input type="date" name="tgl_akhir" class="form-control" value="<?= htmlspecialchars($tgl_akhir) ?>">
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-danger">Tampilkan</button>
            <a href="transaksi.php" class="btn btn-secondary">Reset</a>
            <button type="button" class="btn btn-success" onclick="printLaporan()">
                <i class="bi bi-printer"></i> Cetak PDF
            </button>
        </div>
    </form>

    <div class="table-responsive bg-white shadow-sm rounded p-3" id="laporan-transaksi">
        <table class="table table-bordered table-hover">
            <thead class="table-danger text-center">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama User</th>
                    <th>Jenis</th>
                    <th>Total</th>
                    <th>Metode</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($transaksi) > 0): ?>
                    <?php $no = 1; foreach ($transaksi as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td><?= htmlspecialchars($row['nama_user']) ?></td>
                            <td><?= $row['jenis_transaksi'] ?></td>
                            <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td><?= ucfirst($row['metode_bayar']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada data transaksi yang lunas</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function printLaporan() {
    const content = document.getElementById('laporan-transaksi').innerHTML;
    const win = window.open('', '', 'height=800,width=1000');
    win.document.write(`
      <html>
        <head>
          <title>Laporan Transaksi</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
          <style>
            body { padding: 20px; font-family: Arial, sans-serif; }
            h2 { text-align: center; color: maroon; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #dee2e6; padding: 8px; text-align: center; }
            thead { background-color: #f8d7da; }
          </style>
        </head>
        <body>
          <h2>Laporan Transaksi</h2>
          ${content}
        </body>
      </html>
    `);
    win.document.close();
    win.focus();
    win.print();
    win.close();
}
</script>
