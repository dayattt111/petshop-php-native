<?php include '../../config/koneksi.php'; ?>
<?php include '../includes/header.php'; ?>


<?php 
$mode = 'form';
include '../includes/sidebar.php'; ?>

<div class="container mt-5">
  <h3 class="text-danger mb-4">Tambah Aksesoris</h3>
  <form action="../proses/tambah.php" method="POST" enctype="multipart/form-data">
    
    <div class="mb-3">
      <label for="id_212238">ID Aksesoris</label>
      <input type="number" name="id_212238" id="id_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="nama_aksesoris_212238">Nama Aksesoris</label>
      <input type="text" name="nama_aksesoris_212238" id="nama_aksesoris_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="deskripsi_212238">Deskripsi</label>
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
      <label for="gambar_212238">Gambar</label>
      <input type="file" name="gambar_212238" id="gambar_212238" class="form-control" required>
    </div>

    <button type="submit" name="simpan_aksesoris" class="btn btn-danger">Simpan</button>
    <a href="../aksesoris.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>
