<?php include '../../config/koneksi.php'; ?>
<?php include '../includes/header.php'; ?>
<?php 
$mode = 'form';
include '../includes/sidebar.php'; 
?>

<div class="container mt-5">
  <h3 class="text-danger mb-4">Tambah kasir</h3>
  <form action="../proses/tambah.php" method="POST" enctype="multipart/form-data">

    <div class="mb-3">
      <label>ID kasir</label>
      <input type="text" name="id_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Nama</label>
      <input type="text" name="nama_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Telepon</label>
      <input type="text" name="telepon_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Foto</label>
      <input type="file" name="foto_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir_212238" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Jenis Kelamin</label><br>
      <select name="jenis_kelamin_212238" class="form-control" required>
        <option value="">-- Pilih Jenis Kelamin --</option>
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Alamat</label>
      <textarea name="alamat_212238" class="form-control" rows="3" required></textarea>
    </div>

    <input type="hidden" name="role_212238" value="kasir">

    <button type="submit" name="simpan_kasir" class="btn btn-danger">Simpan</button>
    <a href="../kasir.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>
