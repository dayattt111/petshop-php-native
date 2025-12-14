<?php include '../../config/koneksi.php'; ?>
<?php include '../includes/header.php'; ?>
<?php 
$mode = 'form';
include '../includes/sidebar.php'; 

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM users_212238 WHERE id_212238 = '$id' AND role_212238 = 'kasir'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<div class='alert alert-danger'>Data kasir tidak ditemukan.</div>";
    exit;
}
?>

<div class="container mt-5">
  <h3 class="text-danger mb-4">Edit kasir</h3>
  <form action="../proses/edit.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_212238" value="<?= $data['id_212238']; ?>">

    <div class="mb-3">
      <label>Nama</label>
      <input type="text" name="nama_212238" class="form-control" value="<?= htmlspecialchars($data['nama_212238']); ?>" required>
    </div>

    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username_212238" class="form-control" value="<?= htmlspecialchars($data['username_212238']); ?>" required>
    </div>

    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email_212238" class="form-control" value="<?= htmlspecialchars($data['email_212238']); ?>" required>
    </div>

    <div class="mb-3">
      <label>Telepon</label>
      <input type="text" name="telepon_212238" class="form-control" value="<?= htmlspecialchars($data['telepon_212238']); ?>" required>
    </div>

    <div class="mb-3">
      <label>Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir_212238" class="form-control" value="<?= $data['tanggal_lahir_212238']; ?>" required>
    </div>

    <div class="mb-3">
      <label>Jenis Kelamin</label>
      <select name="jenis_kelamin_212238" class="form-control" required>
        <option value="L" <?= $data['jenis_kelamin_212238'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
        <option value="P" <?= $data['jenis_kelamin_212238'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
      </select>
    </div>
    
    <div class="mb-3">
      <label>Alamat</label>
      <textarea name="alamat_212238" class="form-control" rows="3" required><?= htmlspecialchars($data['alamat_212238']); ?></textarea>
    </div>

    <div class="mb-3">
      <label>Foto (Kosongkan jika tidak ingin mengganti)</label>
      <input type="file" name="foto_212238" class="form-control">
      <small>Foto saat ini: <strong><?= htmlspecialchars($data['foto_212238']); ?></strong></small>
    </div>

    <button type="submit" name="update_kasir" class="btn btn-danger">Update</button>
    <a href="../kasir.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>
