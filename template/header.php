<?php include_once(__DIR__ . '/../config.php'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>PetShop NTI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
    }
    .navbar {
      background-color: #800000 !important;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .navbar-brand {
      color: #fff !important;
      font-weight: bold;
    }
    .nav-link {
      color: #fff !important;
      font-size: 16px;
    }
    .nav-link:hover {
      color: #f8d7da !important;
    }
    .btn-outline-primary {
      border-color: #fff;
      color: #fff;
    }
    .btn-outline-primary:hover {
      background-color: #fff;
      color: #800000 !important;
    }
    .navbar-toggler {
      border: none;
    }
    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255, 255, 255, 1%29' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }
    .navbar-nav {
      margin: auto;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>index.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="me-2" viewBox="0 0 16 16">
        <path d="M8 5.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zM2.5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM8 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
      </svg>
      PetShop NTI
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="menu">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php">Beranda</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="tokoDropdown" role="button" data-bs-toggle="dropdown">
            Toko
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= BASE_URL ?>template/aksesoris.php">Aksesoris</a></li>
            <li><a class="dropdown-item" href="<?= BASE_URL ?>template/pakan.php">Pakan</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>template/periksa.php">Pemeriksaan</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>template/tentang.php">Tentang</a></li>
      </ul>
    </div>
    <a href="<?= BASE_URL ?>login.php" class="btn btn-outline-primary ms-3">Login</a>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
