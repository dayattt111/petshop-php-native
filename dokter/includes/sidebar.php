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
    <h4 class="fw-bold">Dokter Panel</h4>
    <hr class="text-light">
  </div>

  <?php
  echo sidebarLink('index.php', 'bi-speedometer2', 'Dashboard', $mode);
  echo sidebarLink('periksa.php', 'bi-journal-text', 'Data Pemeriksaan', $mode);
  echo sidebarLink('transaksi.php', 'bi-journal-text', 'Transaksi', $mode);
  ?>
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
