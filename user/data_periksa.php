<?php
include "../config/koneksi.php";
include "header.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_user = $_SESSION['user']['id_212238'] ?? null;

$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = $keyword ? "WHERE p.keluhan_212238 LIKE '%$keyword%' OR d.nama_212238 LIKE '%$keyword%'" : "";

$batas = 5;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$mulai = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$count = $koneksi->query("SELECT COUNT(*) as total FROM pemeriksaan_212238 p LEFT JOIN users_212238 d ON p.id_dokter_212238 = d.id_212238 $where");
$total = $count->fetch_assoc()['total'];
$pages = ceil($total / $batas);

$sql = "
  SELECT p.*, d.nama_212238 AS nama_dokter 
  FROM pemeriksaan_212238 p 
  LEFT JOIN users_212238 d ON p.id_dokter_212238 = d.id_212238 
  $where 
  ORDER BY p.tgl_212238 DESC 
  LIMIT $mulai, $batas
";
$result = $koneksi->query($sql);
?>

<div class="container py-5">
  <h3 class="text-center text-maroon fw-bold mb-4">Data Pemeriksaan</h3>

  <form method="get" class="mb-4 text-center">
    <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>" class="form-control d-inline-block w-50" placeholder="Cari keluhan atau nama dokter..." />
    <button class="btn btn-warning text-maroon ms-2">🔍 Cari</button>
  </form>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered text-center align-middle">
        <thead style="background-color:#800000; color:white;">
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keluhan</th>
            <th>Diagnosa</th>
            <th>Resep</th>
            <th>Tindakan</th>
            <th>Catatan</th>
            <th>Dokter</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = $mulai + 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= date("d-m-Y", strtotime($row['tgl_212238'])) ?></td>
              <td><?= nl2br(htmlspecialchars($row['keluhan_212238'])) ?></td>
              <td><?= nl2br(htmlspecialchars($row['diagnosa_212238'] ?? '-')) ?></td>
              <td><?= nl2br(htmlspecialchars($row['resep_212238'] ?? '-')) ?></td>
              <td><?= nl2br(htmlspecialchars($row['tindakan_212238'] ?? '-')) ?></td>
              <td><?= nl2br(htmlspecialchars($row['catatan_212238'] ?? '-')) ?></td>
              <td><?= $row['nama_dokter'] ?? '<em>Belum Ditangani</em>' ?></td>
              <td>
                <?php if ($row['status_212238'] == 'baru'): ?>
                  <span class="badge bg-warning text-dark">Baru</span>
                <?php elseif ($row['status_212238'] == 'selesai'): ?>
                  <span class="badge bg-success">Selesai</span>
                <?php else: ?>
                  <span class="badge bg-danger">Batal</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="edit_pemeriksaan.php?id=<?= $row['id_212238'] ?>" class="btn btn-sm btn-warning text-maroon">✏️</a>
                <button class="btn btn-sm btn-danger btn-hapus" data-id="<?= $row['id_212238'] ?>">🗑️</button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
          <li class="page-item <?= ($halaman == $i) ? 'active' : '' ?>">
            <a class="page-link" style="<?= $halaman == $i ? 'background-color:#800000;color:white;' : '' ?>" href="?halaman=<?= $i ?>&search=<?= urlencode($keyword) ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php else: ?>
    <div class="alert alert-warning text-center">Data pemeriksaan tidak ditemukan.</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-hapus').forEach(btn => {
  btn.addEventListener('click', function() {
    const id = this.dataset.id;
    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: 'Data pemeriksaan akan dihapus permanen!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#800000',
      cancelButtonColor: '#999',
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal'
    }).then(result => {
      if (result.isConfirmed) {
        window.location.href = 'hapus_pemeriksaan.php?id=' + id;
      }
    });
  });
});
</script>

<style>
.text-maroon { color: #800000; }
.btn-warning.text-maroon:hover {
  background-color: #f1c40f;
  color: #800000;
}
.page-link:hover {
  background-color: #f4e04d;
  color: #800000;
}
</style>
