<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role_212238"] !== "admin") {
    header("Location: ../login.php"); // pastikan path login.php benar
    exit;
}

include "includes/header.php";
include "includes/sidebar.php";
include "../config/koneksi.php";

$jumlahUser      = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users_212238 WHERE role_212238 = 'user'"))['total'];
$jumlahDokter    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users_212238 WHERE role_212238 = 'dokter'"))['total'];
$jumlahKasir     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users_212238 WHERE role_212238 = 'kasir'"))['total'];
$jumlahAdmin     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users_212238 WHERE role_212238 = 'admin'"))['total'];

$jumlahPakan         = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pakan_212238"))['total'];
$jumlahAksesoris     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aksesoris_212238"))['total'];
$jumlahPemeriksaan   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pemeriksaan_212238"))['total'];
?>

<!-- Konten Utama -->
<div class="container mt-5">
  <h2 class="text-center text-danger mb-4">Selamat Datang di Dashboard Admin</h2>
  <div class="row justify-content-center g-4">

    <div class="col-md-4">
      <div class="card border-danger shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title">🎀 Aksesoris</h5>
          <p class="card-text">Total: <strong><?= $jumlahAksesoris ?> data</strong></p>
          <a href="aksesoris.php" class="btn btn-danger w-100">Kelola Aksesoris</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-danger shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title">📦 Pakan</h5>
          <p class="card-text">Total: <strong><?= $jumlahPakan ?> data</strong></p>
          <a href="pakan.php" class="btn btn-danger w-100">Kelola Pakan</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-danger shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title">👤 User</h5>
          <p class="card-text">Total: <strong><?= $jumlahUser ?> akun</strong></p>
          <a href="user.php" class="btn btn-danger w-100">Kelola User</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-danger shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title">🩺 Dokter</h5>
          <p class="card-text">Total: <strong><?= $jumlahDokter ?> data</strong></p>
          <a href="dokter.php" class="btn btn-danger w-100">Kelola Dokter</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-danger shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title">📋 Pemeriksaan</h5>
          <p class="card-text">Total: <strong><?= $jumlahPemeriksaan ?> data</strong></p>
          <a href="periksa.php" class="btn btn-danger w-100">Kelola Pemeriksaan</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-danger shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title">💳 Kasir</h5>
          <p class="card-text">Total: <strong><?= $jumlahKasir ?> transaksi</strong></p>
          <a href="kasir.php" class="btn btn-danger w-100">Kelola Kasir</a>
        </div>
      </div>
    </div>

  </div>
</div>
