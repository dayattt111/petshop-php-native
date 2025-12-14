<?php
include '../config/koneksi.php';
include 'includes/header.php';
include 'includes/sidebar.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan'); window.location='periksa.php';</script>";
    exit;
}

$id = $_GET['id'];
$id_dokter = $_SESSION['id_212238'] ?? null;

$data = mysqli_query($koneksi, "
    SELECT p.*, u.nama_212238 as nama_user
    FROM pemeriksaan_212238 p
    JOIN users_212238 u ON p.id_user_212238 = u.id_212238
    WHERE p.id_212238 = '$id'
");

if (mysqli_num_rows($data) == 0) {
    echo "<script>alert('Data tidak ditemukan'); window.location='periksa.php';</script>";
    exit;
}

$pemeriksaan = mysqli_fetch_assoc($data);

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosa = $_POST['diagnosa'];
    $resep = $_POST['resep'];
    $tindakan = $_POST['tindakan'];
    $catatan = $_POST['catatan'];
    $total = $_POST['total'];

    $simpan = mysqli_query($koneksi, "UPDATE pemeriksaan_212238 SET 
        diagnosa_212238 = '$diagnosa',
        resep_212238 = '$resep',
        tindakan_212238 = '$tindakan',
        catatan_212238 = '$catatan',
        status_212238 = 'selesai',
        total_212238 = '$total',
        id_dokter_212238 = '$id_dokter'
        WHERE id_212238 = '$id'
    ");

    if ($simpan) {
        echo "<script>alert('Pemeriksaan berhasil disimpan!'); window.location='periksa.php';</script>";
        exit;
    }
}
?>

<div class="container mt-5">
  <h4 class="text-danger">Form Pemeriksaan - <?= htmlspecialchars($pemeriksaan['nama_user']) ?></h4>
  <div class="card shadow p-4 mt-3">
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Diagnosa</label>
        <textarea name="diagnosa" class="form-control" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Resep</label>
        <textarea name="resep" class="form-control" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Tindakan</label>
        <textarea name="tindakan" class="form-control" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Catatan Tambahan</label>
        <textarea name="catatan" class="form-control"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Total Biaya (Rp)</label>
        <input type="number" name="total" class="form-control" min="0" required>
      </div>
      <div class="text-end">
        <a href="periksa.php" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-danger">Simpan & Selesaikan</button>
      </div>
    </form>
  </div>
</div>
