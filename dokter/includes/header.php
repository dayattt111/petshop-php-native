<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Ganti sesuai folder root project kamu, tanpa trailing slash
$baseUrl = '/rina212238';

$adminNama = isset($_SESSION['user']['nama_212238']) ? $_SESSION['user']['nama_212238'] : 'Kasir';
$adminFoto = isset($_SESSION['user']['foto_212238']) && $_SESSION['user']['foto_212238'] != ''
    ? "../assets/user/" . $_SESSION['user']['foto_212238']
    : "https://ui-avatars.com/api/?name=Kasir&background=8B0000&color=fff"; // fallback avatar
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dokter Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin-left: 250px;
      background-color: #f8f9fa;
    }
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #8B0000;
      color: white;
      padding-top: 1rem;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
    }
    .sidebar a:hover {
      background-color: #a80000;
    }
    .topbar {
      position: fixed;
      top: 0;
      left: 250px;
      right: 0;
      background: linear-gradient(to right, #8B0000, #b30000);
      color: white;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 25px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      z-index: 999;
    }
    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 1.2rem;
    }
    .admin-info {
      display: flex;
      align-items: center;
      gap: 15px;
      font-size: 0.95rem;
    }
    .admin-info span {
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .admin-photo {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid white;
    }
    .btn-warning {
      font-weight: 500;
      transition: background-color 0.2s ease;
    }
    .btn-warning:hover {
      background-color: #e0a800;
      color: #fff;
    }
  </style>
</head>
<body>

<!-- Header / Topbar -->
<div class="topbar">
  <div class="logo">
    <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8 5.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zM2.5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM8 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
    </svg>
    <span class="fw-bold">PetShop NTI</span>
  </div>
  <div class="admin-info">
    <img src="<?= $adminFoto ?>" class="admin-photo" alt="Foto Kasir">
    <span><i class="bi bi-person-circle"></i> <?= htmlspecialchars($adminNama) ?></span>
    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#logoutModal">
      <i class="bi bi-box-arrow-right me-1"></i> Logout
    </button>
  </div>
</div>

<!-- Spacer supaya konten tidak tertutup header -->
<div style="height: 60px;"></div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="logoutModalLabel"><i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin keluar dari akun ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="<?= $baseUrl ?>/keluar.php" class="btn btn-danger">Ya, Logout</a>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
