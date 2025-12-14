<?php
include '../../config/koneksi.php';
include '../includes/header.php';

$mode = 'form';
include '../includes/sidebar.php';

$id = $_GET['id'];
$sql = mysqli_query($koneksi, "SELECT * FROM pakan_212238 WHERE id_212238 = '$id'");
$data = mysqli_fetch_assoc($sql);
?>

<div class="container mt-5">
  <h3 class="text-danger mb-4">Edit Pakan</h3>
  <form action="../proses/edit.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_212238" value="<?= $data['id_212238']; ?>">

    <div class="mb-3">
      <label>Nama Pakan</label>
      <input type="text" name="nama_pakan_212238" class="form-control" value="<?= htmlspecialchars($data['nama_pakan_212238']); ?>" required>
    </div>

    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi_212238" class="form-control" rows="4" required><?= htmlspecialchars($data['deskripsi_212238']); ?></textarea>
    </div>

    <div class="mb-3">
      <label>Harga</label>
      <input type="number" name="harga_212238" class="form-control" value="<?= $data['harga_212238']; ?>" required>
    </div>

    <div class="mb-3">
      <label>Stok</label>
      <input type="number" name="stok_212238" class="form-control" value="<?= $data['stok_212238']; ?>" required>
    </div>

    <div class="mb-3">
      <label>Foto (Kosongkan jika tidak ingin mengganti)</label>
      <input type="file" name="foto_212238" class="form-control">
      <small>Foto saat ini: <strong><?= $data['foto_212238']; ?></strong></small>
    </div>

    <button type="submit" name="update_pakan" class="btn btn-danger">Update</button>
    <a href="../pakan.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>
