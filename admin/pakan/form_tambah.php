<?php 
include '../../config/koneksi.php'; 
include '../includes/header.php'; 

$mode = 'form';
include '../includes/sidebar.php'; 
?>

<div class="container mt-5">
  <h3 class="text-danger mb-4">Tambah Pakan</h3>
  <form action="../proses/tambah.php" method="POST" enctype="multipart/form-data">
    
    <div class="mb-3">
      <label for="id_212238">ID Pakan</label>
      <input type="text" name="id_212238" id="id_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="nama_pakan_212238">Nama Pakan</label>
      <input type="text" name="nama_pakan_212238" id="nama_pakan_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="deskripsi_212223">Deskripsi</label>
      <textarea name="deskripsi_212238" id="deskripsi_212238" class="form-control" rows="4" required></textarea>
    </div>

    <div class="mb-3">
      <label for="harga_212238">Harga</label>
      <input type="number" name="harga_212238" id="harga_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="stok_212238">Stok</label>
      <input type="number" name="stok_212238" id="stok_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="foto_212238">Foto</label>
      <input type="file" name="foto_212238" id="foto_212238" class="form-control" required>
    </div>

    <button type="submit" name="simpan_pakan" class="btn btn-danger">Simpan</button>
    <a href="../pakan.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>
