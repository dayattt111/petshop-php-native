<?php
session_start();
include "../config/koneksi.php";
include "header.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$query = "SELECT * FROM aksesoris_212238";
if (!empty($search)) {
    $s = $koneksi->real_escape_string($search);
    $query .= " WHERE nama_aksesoris_212238 LIKE '%$s%' OR deskripsi_212238 LIKE '%$s%'";
}
$query .= " ORDER BY nama_aksesoris_212238 ASC";

$result = $koneksi->query($query);
?>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-5" id="aksesoris">
  <h2 class="text-center mb-4 text-maroon fw-bold">Koleksi Aksesoris Hewan</h2>

  <!-- Form Pencarian -->
  <form method="get" class="mb-4">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="input-group shadow-sm">
          <input type="text" name="search" class="form-control" placeholder="Cari aksesoris..." value="<?= htmlspecialchars($search) ?>">
          <button class="btn btn-maroon" type="submit">Cari</button>
        </div>
      </div>
    </div>
  </form>

  <!-- Grid Produk -->
  <div class="row g-4">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($data = $result->fetch_assoc()): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card border-0 shadow h-100 product-card">
            <img src="../assets/aksesoris/<?= htmlspecialchars($data['gambar_212238']) ?>"
                 class="card-img-top"
                 alt="<?= htmlspecialchars($data['nama_aksesoris_212238']) ?>"
                 style="height: 200px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title text-maroon"><?= htmlspecialchars($data['nama_aksesoris_212238']) ?></h5>
              <p class="card-text" style="font-size:14px;color:#555;">
                <?= nl2br(htmlspecialchars($data['deskripsi_212238'])) ?>
              </p>
            </div>
            <div class="card-footer bg-white border-0">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-danger">Rp <?= number_format($data['harga_212238'],0,',','.') ?></span>
                <span class="badge bg-success">Stok: <?= $data['stok_212238'] ?></span>
              </div>
              <!-- Tombol Pesan Sekarang -->
              <button type="button" class="btn btn-maroon w-100" onclick="showLoginPopup()">
                Pesan Sekarang
              </button>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-warning text-center">Tidak ada data aksesoris ditemukan.</div>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Popup Login Script -->
<script>
function showLoginPopup() {
  Swal.fire({
    title: 'Harap Login',
    text: 'Anda harus login untuk memesan aksesoris.',
    icon: 'info',
    confirmButtonColor: '#800000',
    confirmButtonText: 'Login Sekarang',
    showCancelButton: true,
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = '../login.php';
    }
  });
}
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Style tambahan -->
<style>
.text-maroon { color: #800000; }
.btn-maroon {
  background-color: #800000;
  color: #fff;
  border: none;
}
.btn-maroon:hover {
  background-color: #a83232;
}
.product-card {
  transition: transform 0.3s ease;
}
.product-card:hover {
  transform: scale(1.02);
}
</style>
