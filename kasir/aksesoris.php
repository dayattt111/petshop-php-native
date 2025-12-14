<?php
include '../config/koneksi.php';
include 'includes/header.php';
$mode = 'main';
include 'includes/sidebar.php';

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Pencarian berdasarkan nama aksesoris
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = $search ? "WHERE nama_aksesoris_212238 LIKE '%$search%'" : "";

// Hitung total data
$total_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aksesoris_212238 $search_sql");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data dengan pagination
$query = "SELECT * FROM aksesoris_212238 $search_sql ORDER BY id_212238 DESC LIMIT $start, $limit";
$sql = mysqli_query($koneksi, $query);
?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-danger">Manajemen Aksesoris</h3>
    <a href="aksesoris/form_tambah.php" class="btn btn-danger">+ Tambah Aksesoris</a>
  </div>

  <!-- Form Pencarian -->
  <form method="GET" class="mb-3 d-flex" style="max-width: 400px;">
    <input type="text" name="search" class="form-control me-2" placeholder="Cari aksesoris..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn btn-outline-danger">Cari</button>
  </form>

  <div class="table-responsive shadow-sm border rounded p-3 bg-white">
    <table class="table table-bordered table-hover">
      <thead class="table-danger text-center">
        <tr>
          <th>No</th>
          <th>Gambar</th>
          <th>Nama</th>
          <th>Deskripsi</th>
          <th>Harga</th>
          <th>Stok</th>
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
            <img src="../assets/aksesoris/<?= htmlspecialchars($data['gambar_212238']); ?>" width="60" height="60" style="object-fit: cover; border-radius: 8px;">
          </td>
          <td><?= htmlspecialchars($data['nama_aksesoris_212238']); ?></td>
          <td><?= substr(htmlspecialchars($data['deskripsi_212238']), 0, 50); ?>...</td>
          <td>Rp<?= number_format($data['harga_212238'], 0, ',', '.'); ?></td>
          <td><?= $data['stok_212238']; ?></td>
          <td class="text-center">
            <a href="aksesoris/form_edit.php?id=<?= $data['id_212238']; ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="#" 
               class="btn btn-sm btn-danger btn-hapus" 
               data-id="<?= $data['id_212238']; ?>" 
               data-nama="<?= htmlspecialchars($data['nama_aksesoris_212238']); ?>"
               data-jenis="aksesoris">
               Hapus
            </a>
          </td>
        </tr>
        <?php } 
        } else {
          echo "<tr><td colspan='7' class='text-center'>Data tidak ditemukan</td></tr>";
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

<!-- SweetAlert2 CDN -->
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
        text: `Aksesoris "${nama}" akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Kirim ke hapus.php dengan parameter jenis dan id
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
