<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role_212238"] !== "dokter") {
    header("Location: ../login.php"); // pastikan path login.php benar
    exit;
}

include "includes/header.php";
include "includes/sidebar.php";
include "../config/koneksi.php";



// Ambil ID dokter login
$id_dokter = $_SESSION['user']['id_212238'] ?? '';

// Ambil data statistik
$jml_baru = $koneksi->query("SELECT COUNT(*) as total FROM pemeriksaan_212238 WHERE id_dokter_212238='$id_dokter' AND status_212238='baru'")->fetch_assoc()['total'];
$jml_hari_ini = $koneksi->query("SELECT COUNT(*) as total FROM pemeriksaan_212238 WHERE id_dokter_212238='$id_dokter' AND tgl_212238 = CURDATE()")->fetch_assoc()['total'];
$jml_total = $koneksi->query("SELECT COUNT(*) as total FROM pemeriksaan_212238 WHERE id_dokter_212238='$id_dokter'")->fetch_assoc()['total'];

$nama_dokter = $_SESSION['user']['nama_212238'] ?? 'Dokter';
?>

<div class="container py-5">
  <h2 class="text-center text-maroon fw-bold mb-4">Dashboard Dokter</h2>
  <div class="alert alert-warning text-center fw-semibold fs-5">
    Selamat datang, <span class="text-maroon"><?= htmlspecialchars($nama_dokter) ?></span> 👨‍⚕️
  </div>

  <div class="row g-4 mt-4">
    <div class="col-md-4">
      <div class="card shadow border-0">
        <div class="card-body text-center">
          <i class="bi bi-person-plus-fill fs-1 text-warning"></i>
          <h5 class="mt-3">Pasien Baru</h5>
          <h2 class="text-maroon"><?= $jml_baru ?></h2>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow border-0">
        <div class="card-body text-center">
          <i class="bi bi-calendar-event-fill fs-1 text-warning"></i>
          <h5 class="mt-3">Hari Ini</h5>
          <h2 class="text-maroon"><?= $jml_hari_ini ?></h2>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow border-0">
        <div class="card-body text-center">
          <i class="bi bi-clipboard2-pulse-fill fs-1 text-warning"></i>
          <h5 class="mt-3">Total Pemeriksaan</h5>
          <h2 class="text-maroon"><?= $jml_total ?></h2>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-5 text-center">
   
    <a href="periksa.php" class="btn btn-outline-maroon fw-semibold ms-2">
      <i class="bi bi-clipboard-plus me-1"></i> Periksa Pasien
    </a>
  </div>
</div>

<!-- Style -->
<style>
.text-maroon {
  color: #800000;
}
.btn-outline-maroon {
  border: 2px solid #800000;
  color: #800000;
}
.btn-outline-maroon:hover {
  background-color: #800000;
  color: white;
}
</style>
