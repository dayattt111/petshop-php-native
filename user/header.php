<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$nama_user = $_SESSION['user']['nama_212238'];
$foto_user = $_SESSION['user']['foto_212238'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Petshop - User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar-custom {
      background-color: #f8f9fa;
      border-bottom: 1px solid #ddd;
    }
    .navbar-custom .profile-img {
      width: 40px;
      height: 40px;
      object-fit: cover;
      border-radius: 50%;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom py-2">
  <div class="container-fluid justify-content-between">
    
    <!-- Brand -->
    <a class="navbar-brand fw-bold text-danger ms-3" href="index.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="d-inline-block align-text-top me-1" viewBox="0 0 16 16">
        <path d="M8 5.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zM2.5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM8 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
      </svg>
      PetShop NTI
    </a>

    <!-- Navbar tengah -->
    <div class="d-flex justify-content-center flex-grow-1">
      <ul class="navbar-nav mb-2 mb-lg-0 d-flex flex-row gap-3">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="aksesoris.php">Aksesoris</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pakan.php">Pakan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="periksa.php">Pemeriksaan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pesanan.php">Data Pesanan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="data_periksa.php">Data Pemeriksaan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-primary fw-bold" href="prediksi_layanan.php">🤖 AI Prediksi</a>
        </li>
      </ul>
    </div>

    <!-- Profil dan logout -->
    <div class="d-flex align-items-center gap-2 me-3">
      <span class="fw-semibold"><?= htmlspecialchars($nama_user) ?></span>
      <img src="../assets/user/<?= htmlspecialchars($foto_user) ?>" class="profile-img" alt="Foto Profil">
      <a href="../logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
