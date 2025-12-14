<?php
include '../config/koneksi.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Konfigurasi pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_escaped = mysqli_real_escape_string($koneksi, $search);
$search_sql = $search ? "AND nama_212238 LIKE '%$search_escaped%'" : "";

// Hitung total data user
$total_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users_212238 WHERE role_212238 = 'user' $search_sql");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data user
$sql = mysqli_query($koneksi, "SELECT * FROM users_212238 WHERE role_212238 = 'user' $search_sql ORDER BY id_212238 DESC LIMIT $start, $limit");
?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-danger">Data User</h3>
    <a href="user/form_tambah.php" class="btn btn-danger">+ Tambah User</a>
  </div>

  <!-- Form Pencarian -->
  <form method="GET" class="mb-3 d-flex" style="max-width: 400px;">
    <input type="text" name="search" class="form-control me-2" placeholder="Cari nama user..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn btn-outline-danger">Cari</button>
  </form>

  <div class="table-responsive shadow-sm border rounded p-3 bg-white">
    <table class="table table-bordered table-hover">
      <thead class="table-danger text-center">
        <tr>
          <th>No</th>
          <th>Foto</th>
          <th>Nama</th>
          <th>Username</th>
          <th>Email</th>
          <th>No. Telepon</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = $start + 1;
        if (mysqli_num_rows($sql) > 0) {
          while ($data = mysqli_fetch_array($sql)) {
        ?>
        <tr>
          <td class="text-center"><?= $no++; ?></td>
          <td class="text-center">
            <img src="../assets/user/<?= htmlspecialchars($data['foto_212238']); ?>" width="60" height="60" style="object-fit: cover; border-radius: 50%;">
          </td>
          <td><?= htmlspecialchars($data['nama_212238']); ?></td>
          <td><?= htmlspecialchars($data['username_212238']); ?></td>
          <td><?= htmlspecialchars($data['email_212238']); ?></td>
          <td><?= htmlspecialchars($data['telepon_212238']); ?></td>
          <td class="text-center">
            <a href="user/form_edit.php?id=<?= $data['id_212238']; ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="proses/hapus.php" 
              class="btn btn-sm btn-danger btn-hapus" 
              data-id="<?= $data['id_212238']; ?>" 
              data-nama="<?= htmlspecialchars($data['nama_212238']); ?>" 
              data-jenis="user">
              Hapus
            </a>
          </td>
        </tr>
        <?php
          }
        } else {
          echo "<tr><td colspan='7' class='text-center'>Data tidak ditemukan</td></tr>";
        }
        ?>

         <!-- Tempatkan di bawah semua konten HTML -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.querySelectorAll('.btn-hapus').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();

      const id = this.getAttribute('data-id');
      const nama = this.getAttribute('data-nama');
      const jenis = this.getAttribute('data-jenis');

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: `Data "${nama}" akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `proses/hapus.php?jenis=${jenis}&id=${id}`;
        }
      });
    });
  });

  <?php if (isset($_GET['hapus']) && $_GET['hapus'] == 'success'): ?>
  Swal.fire({
    title: 'Berhasil!',
    text: 'Data berhasil dihapus.',
    icon: 'success',
    showConfirmButton: false,
    timer: 2000
  });
  <?php endif; ?>
</script>
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
