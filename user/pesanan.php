<?php
include "../config/koneksi.php";
include "header.php";

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role_212238'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['user']['id_212238'];

$query = "
    SELECT 
        p.*, 
        a.nama_aksesoris_212238 AS nama_barang, 
        a.gambar_212238 AS gambar, 
        a.harga_212238
    FROM pesanan_212238 p
    JOIN aksesoris_212238 a ON p.id_barang_212238 = a.id_212238
    WHERE p.id_user_212238 = '$id_user' AND p.jenis_212238 = 'aksesoris'
    
    UNION ALL

    SELECT 
        p.*, 
        b.nama_pakan_212238 AS nama_barang, 
        b.foto_212238 AS gambar, 
        b.harga_212238
    FROM pesanan_212238 p
    JOIN pakan_212238 b ON p.id_barang_212238 = b.id_212238
    WHERE p.id_user_212238 = '$id_user' AND p.jenis_212238 = 'pakan'
    
    ORDER BY waktu_input_212238 DESC
";

$result = $koneksi->query($query);
?>

<div class="container py-5">
  <h3 class="text-center mb-4 text-maroon fw-bold">Pesanan Saya</h3>

  <div class="row mb-3">
    <div class="col-md-6 offset-md-3">
      <input type="text" id="searchInput" class="form-control shadow-sm" placeholder="🔍 Cari berdasarkan nama barang...">
    </div>
  </div>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center" id="pesananTable">
        <thead class="table-maroon text-white">
          <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Gambar</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
            <th>Alamat</th>
            <th>Catatan</th>
            <th>Metode</th>
            <th>Status Bayar</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 1; 
            while ($row = $result->fetch_assoc()): 
              $total_harga = $row['jumlah_212238'] * $row['harga_212238'];
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td class="nama-barang"><?= htmlspecialchars($row['nama_barang']) ?></td>
              <td><img src="../assets/<?= $row['jenis_212238'] ?>/<?= $row['gambar'] ?>" width="60" class="rounded shadow-sm"></td>
              <td><?= $row['jumlah_212238'] ?></td>
              <td>Rp <?= number_format($row['harga_212238'], 0, ',', '.') ?></td>
              <td>Rp <?= number_format($total_harga, 0, ',', '.') ?></td>
              <td class="text-start"><?= nl2br(htmlspecialchars($row['alamat_212238'])) ?></td>
              <td class="text-start"><?= nl2br(htmlspecialchars($row['catatan_212238'])) ?></td>
              <td><?= ucfirst($row['metode_bayar_212238']) ?></td>
              <td><?= ucfirst($row['status_bayar_212238']) ?></td>
              <td><?= ucfirst($row['status_pesanan_212238']) ?></td>
              <td><?= date("d-m-Y H:i", strtotime($row['waktu_input_212238'])) ?></td>
              <td>
                <a href="edit_pesanan.php?id=<?= $row['id_212238'] ?>" class="btn btn-sm btn-warning me-1">
                  Edit
                </a>
                <button class="btn btn-sm btn-danger btn-hapus" data-id="<?= $row['id_212238'] ?>">
                  Hapus
                </button>
              </td>

            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="text-center mt-3" id="pagination"></div>
  <?php else: ?>
    <div class="alert alert-warning text-center">Anda belum memiliki pesanan.</div>
  <?php endif; ?>
</div>

<!-- SweetAlert2 & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Script Hapus -->
<script>
document.querySelectorAll('.btn-hapus').forEach(button => {
  button.addEventListener('click', function () {
    const id = this.getAttribute('data-id');
    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: "Pesanan akan dihapus permanen.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = `hapus_pesanan.php?id=${id}`;
      }
    });
  });
});
</script>

<!-- Script Pencarian & Pagination -->
<script>
$(document).ready(function () {
  const rowsPerPage = 5;
  let rows = $('#pesananTable tbody tr');
  let totalPages = Math.ceil(rows.length / rowsPerPage);

  function showPage(page) {
    rows.hide();
    rows.slice((page - 1) * rowsPerPage, page * rowsPerPage).show();
  }

  function renderPagination() {
    $('#pagination').empty();
    for (let i = 1; i <= totalPages; i++) {
      $('#pagination').append(`<button class="btn btn-outline-maroon mx-1 page-btn" data-page="${i}">${i}</button>`);
    }
    $('.page-btn').first().addClass('active');
  }

  $('#pagination').on('click', '.page-btn', function () {
    $('.page-btn').removeClass('active');
    $(this).addClass('active');
    showPage($(this).data('page'));
  });

  $('#searchInput').on('keyup', function () {
    const val = $(this).val().toLowerCase();
    rows.each(function () {
      const match = $(this).find('.nama-barang').text().toLowerCase().includes(val);
      $(this).toggle(match);
    });
  });

  if (rows.length > 0) {
    renderPagination();
    showPage(1);
  }
});
</script>

<!-- Style -->
<style>
.table-maroon {
  background-color: #800000;
}
.text-maroon {
  color: #800000;
}
.btn-sm {
  font-size: 0.85rem;
  padding: 6px 10px;
  border-radius: 6px;
}
.btn-outline-maroon {
  border: 1px solid #800000;
  color: #800000;
}
.btn-outline-maroon:hover,
.page-btn.active {
  background-color: #800000;
  color: #fff;
}
</style>
<style>
.btn-sm {
  font-size: 0.85rem;
  padding: 5px 10px;
  transition: 0.2s ease-in-out;
}
.btn-warning:hover {
  background-color: #e0a800;
  color: #fff;
}
.btn-danger:hover {
  background-color: #c82333;
}
</style>
