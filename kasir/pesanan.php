<?php ob_start(); ?>

<?php

include '../config/koneksi.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_escaped = mysqli_real_escape_string($koneksi, $search);
$search_sql = $search ? "AND u.nama_212238 LIKE '%$search_escaped%'" : "";

// Total Data
$total_query = mysqli_query($koneksi, "
  SELECT COUNT(*) as total 
  FROM pesanan_212238 p
  JOIN users_212238 u ON p.id_user_212238 = u.id_212238
  WHERE 1=1 $search_sql
");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data pesanan
$query = mysqli_query($koneksi, "
  SELECT p.*, u.nama_212238,
    CASE 
      WHEN p.jenis_212238 = 'pakan' THEN (SELECT nama_pakan_212238 FROM pakan_212238 WHERE id_212238 = p.id_barang_212238)
      WHEN p.jenis_212238 = 'aksesoris' THEN (SELECT nama_aksesoris_212238 FROM aksesoris_212238 WHERE id_212238 = p.id_barang_212238)
      ELSE 'Tidak Diketahui'
    END AS nama_barang
  FROM pesanan_212238 p
  JOIN users_212238 u ON p.id_user_212238 = u.id_212238
  WHERE 1=1 $search_sql
  ORDER BY p.waktu_input_212238 DESC
  LIMIT $start, $limit
");

// Proses update status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['id_pesanan']) && isset($_POST['aksi']) && $_POST['aksi'] == 'bayar') {
    $id = $_POST['id_pesanan'];
    mysqli_query($koneksi, "UPDATE pesanan_212238 SET status_bayar_212238 = 'sudah' WHERE id_212238 = '$id'");
    header("Location: pesanan.php?bayar=success");
    exit;
  }

  if (isset($_POST['id_pesanan']) && isset($_POST['status_pesanan'])) {
    $id = $_POST['id_pesanan'];
    $status = $_POST['status_pesanan'];
    mysqli_query($koneksi, "UPDATE pesanan_212238 SET status_pesanan_212238 = '$status' WHERE id_212238 = '$id'");
    header("Location: pesanan.php?status=success");
    exit;
  }
}
?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-danger">Data Pesanan User</h3>
  </div>

  <form method="GET" class="mb-3 d-flex" style="max-width: 400px;">
    <input type="text" name="search" class="form-control me-2" placeholder="Cari nama user..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn btn-outline-danger">Cari</button>
  </form>

  <div class="table-responsive shadow-sm border rounded p-3 bg-white">
    <table class="table table-bordered table-hover">
      <thead class="table-danger text-center">
        <tr>
          <th>No</th>
          <th>Nama User</th>
          <th>Jenis</th>
          <th>Nama Barang</th>
          <th>Jumlah</th>
          <th>Total</th>
          <th>Alamat</th>
          <th>Metode</th>
          <th>Status Bayar</th>
          <th>Status Pesanan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = $start + 1;
        if (mysqli_num_rows($query) > 0) {
          while ($row = mysqli_fetch_array($query)) {
        ?>
        <tr class="text-center align-middle">
          <td><?= $no++; ?></td>
          <td><?= htmlspecialchars($row['nama_212238']); ?></td>
          <td><?= ucfirst($row['jenis_212238']); ?></td>
          <td><?= htmlspecialchars($row['nama_barang']); ?></td>
          <td><?= $row['jumlah_212238']; ?></td>
          <td>Rp <?= number_format($row['total_harga_212238'], 0, ',', '.'); ?></td>
          <td><?= htmlspecialchars($row['alamat_212238']); ?></td>
          <td><?= strtoupper($row['metode_bayar_212238']); ?></td>
          <td>
            <?php if ($row['status_bayar_212238'] == 'sudah'): ?>
              <span class="badge bg-success">Sudah</span>
            <?php else: ?>
              <form method="post" onsubmit="return confirmBayar(this);">
                <input type="hidden" name="id_pesanan" value="<?= $row['id_212238']; ?>">
                <input type="hidden" name="aksi" value="bayar">
                <button type="submit" class="btn btn-sm btn-success">Tandai Lunas</button>
              </form>
            <?php endif; ?>
          </td>
          <td>
            <form method="post" class="d-flex align-items-center justify-content-center">
              <input type="hidden" name="id_pesanan" value="<?= $row['id_212238']; ?>">
              <select name="status_pesanan" class="form-select form-select-sm me-1" onchange="this.form.submit()">
                <?php
                  $statuses = ['pending', 'diproses', 'dikirim', 'selesai'];
                  foreach ($statuses as $status) {
                    $selected = ($row['status_pesanan_212238'] == $status) ? 'selected' : '';
                    echo "<option value='$status' $selected>" . ucfirst($status) . "</option>";
                  }
                ?>
              </select>
            </form>
          </td>
          <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
        </tr>
        <?php
          }
        } else {
          echo "<tr><td colspan='11' class='text-center'>Data tidak ditemukan</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmBayar(form) {
    event.preventDefault();
    Swal.fire({
      title: 'Tandai sebagai sudah bayar?',
      text: "Tindakan ini tidak dapat dibatalkan.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#aaa',
      confirmButtonText: 'Ya, tandai lunas!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
    return false;
  }

  <?php if (isset($_GET['bayar']) && $_GET['bayar'] == 'success'): ?>
  Swal.fire({
    title: 'Berhasil!',
    text: 'Status pembayaran diperbarui.',
    icon: 'success',
    showConfirmButton: false,
    timer: 2000
  });
  <?php endif; ?>

  <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
  Swal.fire({
    title: 'Berhasil!',
    text: 'Status pesanan berhasil diubah.',
    icon: 'success',
    showConfirmButton: false,
    timer: 2000
  });
  <?php endif; ?>
</script>
<?php ob_end_flush(); ?>
