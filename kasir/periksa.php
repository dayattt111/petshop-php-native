<?php
include '../config/koneksi.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_sql = $search ? "AND u.nama_212238 LIKE '%$search%'" : '';

// Total data
$total_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pemeriksaan_212238 p JOIN users_212238 u ON p.id_user_212238 = u.id_212238 WHERE 1 $search_sql");
$total_data = mysqli_fetch_assoc($total_q)['total'];
$total_pages = ceil($total_data / $limit);

// Query data
$sql = mysqli_query($koneksi, "
  SELECT p.*, u.nama_212238 as nama_user, d.nama_212238 as nama_dokter
  FROM pemeriksaan_212238 p
  LEFT JOIN users_212238 u ON p.id_user_212238 = u.id_212238
  LEFT JOIN users_212238 d ON p.id_dokter_212238 = d.id_212238
  WHERE 1 $search_sql
  ORDER BY p.tgl_212238 DESC
  LIMIT $start, $limit
");

// Tangani aksi bayar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'], $_POST['id_pemeriksaan']) && $_POST['aksi'] == 'bayar') {
    $id = $_POST['id_pemeriksaan'];
    $koneksi->query("UPDATE pemeriksaan_212238 SET status_bayar_212238 = 'sudah' WHERE id_212238 = '$id'");
    echo "<script>window.location.href = '".$_SERVER['PHP_SELF']."';</script>";
    exit;
}
?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-danger">Data Pemeriksaan Hewan</h3>
  </div>

  <!-- Form Pencarian -->
  <form class="mb-3 d-flex" method="GET" style="max-width: 400px;">
    <input type="text" name="search" class="form-control me-2" placeholder="Cari nama user..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-outline-danger">Cari</button>
  </form>

  <div class="table-responsive bg-white shadow-sm rounded p-3">
    <table class="table table-bordered table-hover text-center align-middle">
      <thead class="table-danger">
        <tr>
          <th>No</th>
          <th>Tanggal</th>
          <th>Nama User</th>
          <th>Keluhan</th>
          <th>Dokter</th>
          <th>Status</th>
          <th>Status Bayar</th>
          <th>Metode Bayar</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = $start + 1;
        if (mysqli_num_rows($sql) > 0) {
          while ($data = mysqli_fetch_assoc($sql)) {
        ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= htmlspecialchars($data['tgl_212238']) ?></td>
          <td><?= htmlspecialchars($data['nama_user']) ?></td>
          <td><?= htmlspecialchars($data['keluhan_212238']) ?></td>
          <td><?= $data['nama_dokter'] ?? '<span class="text-muted fst-italic">Belum ditangani</span>' ?></td>
          <td>
            <span class="badge bg-<?= 
              $data['status_212238'] == 'baru' ? 'warning' : 
              ($data['status_212238'] == 'selesai' ? 'success' : 'secondary') ?>">
              <?= ucfirst($data['status_212238']) ?>
            </span>
          </td>
          <td>
            <?php if ($data['status_bayar_212238'] == 'sudah'): ?>
              <span class="badge bg-success">Sudah</span>
            <?php else: ?>
              <form method="post" onsubmit="return confirm('Yakin ingin menandai sebagai lunas?');">
                <input type="hidden" name="id_pemeriksaan" value="<?= $data['id_212238']; ?>">
                <input type="hidden" name="aksi" value="bayar">
                <button type="submit" class="btn btn-sm btn-success">Tandai Lunas</button>
              </form>
            <?php endif; ?>
          </td>
          <td><?= strtoupper($data['metode_bayar_212238'] ?? '-') ?></td>
        </tr>
        <?php
          }
        } else {
          echo "<tr><td colspan='8' class='text-center text-muted'>Tidak ada data ditemukan</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <nav class="mt-3">
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">«</a></li>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
          <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
      <?php if ($page < $total_pages): ?>
        <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">»</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</div>
