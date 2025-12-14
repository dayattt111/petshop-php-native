<?php
include "../config/koneksi.php";
include "header.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan'); window.location='data_periksa.php';</script>";
    exit;
}

$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM pemeriksaan_212238 WHERE id_212238 = '$id'")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $keluhan = $_POST['keluhan'];
    $tanggal = $_POST['tgl'];

    $update = $koneksi->query("UPDATE pemeriksaan_212238 SET 
        keluhan_212238 = '$keluhan',
        tgl_212238 = '$tanggal'
        WHERE id_212238 = '$id'
    ");

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    if ($update) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data pemeriksaan berhasil diperbarui.',
                confirmButtonColor: '#800000'
            }).then(() => {
                window.location.href = 'data_periksa.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Data gagal diperbarui. Silakan coba lagi.',
                confirmButtonColor: '#800000'
            });
        </script>";
    }
}
?>

<div class="container py-4">
  <h3 class="mb-4" style="color: #800000;">Edit Konsultasi Pemeriksaan</h3>
  <form method="post">
    <div class="mb-3">
      <label class="form-label" style="color:#800000;">Keluhan</label>
      <textarea name="keluhan" class="form-control" rows="4" required><?= htmlspecialchars($data['keluhan_212238']) ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label" style="color:#800000;">Tanggal Pemeriksaan</label>
      <input type="date" name="tgl" class="form-control" value="<?= htmlspecialchars($data['tgl_212238']) ?>" required>
    </div>
    <button type="submit" class="btn btn-warning">💾 Simpan Perubahan</button>
    <a href="data_periksa.php" class="btn btn-secondary">↩️ Kembali</a>
  </form>
</div>

<style>
  .btn-warning {
    background-color: #FFD700;
    color: #800000;
    border: none;
  }
  .btn-warning:hover {
    background-color: #ffc107;
    color: #fff;
  }
</style>
