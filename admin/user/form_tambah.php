
<?php include '../../config/koneksi.php'; ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container mt-5">
  <h3 class="text-danger mb-4">Tambah User</h3>
  <form action="../proses/tambah.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>No. Telepon</label>
      <input type="text" name="telepon" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Foto</label>
      <input type="file" name="foto" class="form-control" required>
    </div>
    <button type="submit" name="simpan_user" class="btn btn-danger">Simpan</button>
    <a href="../kasir.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>
