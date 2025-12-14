<?php
// Tentukan mode halaman: 'main' untuk halaman daftar, 'form' untuk tambah/edit
// Pastikan kamu set variabel $mode di setiap halaman sebelum include sidebar ini.
// Contoh di halaman tambah.php dan edit.php: $mode = 'form';
// Contoh di halaman index.php: $mode = 'main';

if (!isset($mode)) {
    $mode = 'main'; // default ke main agar aman
}

function sidebarLink($href, $icon, $text, $mode) {
    if ($mode === 'form') {
        // Nonaktifkan link saat mode form
        return '<a href="#" class="sidebar-link disabled" style="cursor: default;">' .
               '<i class="bi ' . $icon . ' me-2"></i> ' . $text . '</a>';
    } else {
        // Link aktif normal
        return '<a href="' . $href . '" class="sidebar-link">' .
               '<i class="bi ' . $icon . ' me-2"></i> ' . $text . '</a>';
    }
}
?>

<div class="sidebar bg-dark text-white p-3" style="min-height: 100vh; width: 250px; position: fixed;">
  <div class="text-center mb-4">
    <h4 class="fw-bold">👨‍💼 Admin Panel</h4>
    <hr class="text-light">
  </div>

  <?php
  echo sidebarLink('index.php', 'bi-speedometer2', 'Dashboard', $mode);
  echo sidebarLink('aksesoris.php', 'bi-gem', 'Aksesoris', $mode);
  echo sidebarLink('pakan.php', 'bi-box-seam', 'Pakan', $mode);
  echo sidebarLink('dokter.php', 'bi-person-badge', 'Dokter', $mode);
  echo sidebarLink('kasir.php', 'bi-credit-card-2-front', 'Kasir', $mode);
  echo sidebarLink('user.php', 'bi-people', 'User', $mode);
  echo sidebarLink('periksa.php', 'bi-journal-text', 'Data Pemeriksaan', $mode);
  echo sidebarLink('pesanan.php', 'bi-journal-text', 'Data Pesanan', $mode);
  echo sidebarLink('transaksi.php', 'bi-journal-text', 'Transaksi', $mode);
  ?>
  
  <hr class="text-light my-3">
  
  <!-- ML System Menu -->
  <div class="mb-2">
    <div class="text-warning fw-bold px-3 py-2" style="font-size: 0.85rem;">
      <i class="bi bi-robot me-2"></i> ML SYSTEM
    </div>
    <?php
    echo sidebarLink('ml_management.php', 'bi-cpu', 'ML Management', $mode);
    echo sidebarLink('ml_preview.php', 'bi-eye', 'Preview All Roles', $mode);
    ?>
    <hr class="text-light my-2">
    <div class="text-muted px-3 py-1" style="font-size: 0.75rem;">Direct Access:</div>
    <?php
    echo sidebarLink('../kasir/prediksi_penjualan.php', 'bi-graph-up-arrow', 'Kasir View', $mode);
    echo sidebarLink('../dokter/ai_assistant.php', 'bi-capsule', 'Dokter View', $mode);
    echo sidebarLink('../user/prediksi_layanan.php', 'bi-heart-pulse', 'User View', $mode);
    ?>
  </div>
</div>

<style>
  .sidebar-link {
    display: block;
    padding: 12px 15px;
    margin-bottom: 6px;
    color: #fff;
    text-decoration: none;
    border-radius: 0.375rem;
    transition: 0.2s ease;
  }

  .sidebar-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    padding-left: 20px;
    text-decoration: none;
    color: #ffc107;
  }

  /* Style untuk link nonaktif saat di mode form */
  .sidebar-link.disabled {
    pointer-events: none; /* Tidak bisa diklik */
    opacity: 0.6;         /* Transparan */
  }
</style>

<!-- Ikon Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
