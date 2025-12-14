<?php
include '../config/koneksi.php';
include 'includes/header.php';
include 'includes/sidebar.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$query = mysqli_query($koneksi, "
    SELECT p.*, u.nama_212238 AS nama_user
    FROM pemeriksaan_212238 p
    JOIN users_212238 u ON p.id_user_212238 = u.id_212238
    WHERE p.status_212238 = 'baru'
    ORDER BY p.tgl_212238 DESC
");
?>

<div class="container mt-5">
  <h3 class="text-danger mb-4">Pemeriksaan Masuk</h3>

  <div class="table-responsive bg-white shadow-sm rounded p-3">
    <table class="table table-bordered table-hover text-center align-middle">
      <thead class="table-warning text-dark">
        <tr>
          <th>No</th>
          <th>Nama User</th>
          <th>Tanggal</th>
          <th>Keluhan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        if (mysqli_num_rows($query) > 0):
          while ($row = mysqli_fetch_assoc($query)):
        ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($row['nama_user']) ?></td>
          <td><?= htmlspecialchars($row['tgl_212238']) ?></td>
          <td><?= htmlspecialchars($row['keluhan_212238']) ?></td>
          <td>
            <a href="hasil_periksa.php?id=<?= $row['id_212238'] ?>" class="btn btn-sm btn-danger">Periksa</a>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="5" class="text-muted">Tidak ada pemeriksaan baru.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
