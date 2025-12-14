<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role_212238"] !== "kasir") {
    header("Location: ../login.php"); // pastikan path login.php benar
    exit;
}

include "includes/header.php";
include "includes/sidebar.php";
include "../config/koneksi.php";


$nama_kasir = $_SESSION['user']['nama_212238'] ?? 'Kasir';

// Ambil data pesanan
$total_pesanan = $koneksi->query("SELECT COUNT(*) as total FROM pesanan_212238")->fetch_assoc()['total'];
$total_item = $koneksi->query("SELECT SUM(jumlah_212238) as total FROM pesanan_212238")->fetch_assoc()['total'] ?? 0;
$pesanan_hari_ini = $koneksi->query("SELECT COUNT(*) as total FROM pesanan_212238 WHERE DATE(tgl_212238) = CURDATE()")->fetch_assoc()['total'];

// Ambil data pemeriksaan
$total_pemeriksaan = $koneksi->query("SELECT COUNT(*) as total FROM pemeriksaan_212238")->fetch_assoc()['total'];
$pemeriksaan_hari_ini = $koneksi->query("SELECT COUNT(*) as total FROM pemeriksaan_212238 WHERE DATE(tgl_212238) = CURDATE()")->fetch_assoc()['total'];
?>

<div class="container py-5">
  <h2 class="text-center text-maroon fw-bold mb-4">Dashboard Kasir</h2>

  <div class="alert alert-warning text-center fw-semibold fs-5">
    Selamat datang, <span class="text-maroon"><?= htmlspecialchars($nama_kasir) ?></span> 👋
  </div>

  <div class="row g-4 mt-4">
    <div class="col-md-3">
      <div class="card border-0 shadow text-center">
        <div class="card-body">
          <i class="bi bi-box2-fill fs-1 text-warning"></i>
          <h5>Total Pesanan</h5>
          <h2 class="text-maroon"><?= $total_pesanan ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow text-center">
        <div class="card-body">
          <i class="bi bi-cart-check-fill fs-1 text-warning"></i>
          <h5>Total Item</h5>
          <h2 class="text-maroon"><?= $total_item ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow text-center">
        <div class="card-body">
          <i class="bi bi-clipboard-pulse fs-1 text-warning"></i>
          <h5>Total Pemeriksaan</h5>
          <h2 class="text-maroon"><?= $total_pemeriksaan ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow text-center">
        <div class="card-body">
          <i class="bi bi-calendar-day fs-1 text-warning"></i>
          <h5>Pemeriksaan Hari Ini</h5>
          <h2 class="text-maroon"><?= $pemeriksaan_hari_ini ?></h2>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-5 d-flex justify-content-center gap-3">
    <a href="pesanan.php" class="btn btn-warning text-maroon fw-semibold">
      <i class="bi bi-list-check me-1"></i> Kelola Pesanan
    </a>
    <a href="periksa.php" class="btn btn-danger text-white fw-semibold">
      <i class="bi bi-journal-medical me-1"></i> Kelola Pemeriksaan
    </a>
  </div>
</div>

<!-- Style -->
<style>
.text-maroon {
  color: #800000;
}
.btn-warning.text-maroon:hover {
  background-color: #f1c40f;
  color: #800000;
}
.card-body h2 {
  font-weight: bold;
}
</style>
