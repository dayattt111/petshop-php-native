<?php
include '../../config/koneksi.php'; // Path ke koneksi

if (!isset($_GET['jenis']) || !isset($_GET['id'])) {
    echo "Parameter tidak lengkap.";
    exit;
}

$jenis = $_GET['jenis'];
$id    = $_GET['id'];

// Konfigurasi berdasarkan jenis
switch ($jenis) {
    case 'aksesoris':
        $tabel         = 'aksesoris_212238';
        $kolom_id      = 'id_212238';
        $kolom_gambar  = 'gambar_212238';
        $folder_gambar = '../assets/aksesoris/';
        $redirect      = '../aksesoris.php?hapus=success';
        break;

    case 'pakan':
        $tabel         = 'pakan_212238';
        $kolom_id      = 'id_212238';
        $kolom_gambar  = 'foto_212238';
        $folder_gambar = '../assets/pakan/';
        $redirect      = '../pakan.php?hapus=success';
        break;

    case 'dokter':
        $tabel         = 'users_212238';
        $kolom_id      = 'id_212238';
        $kolom_gambar  = 'foto_212238';
        $folder_gambar = '../assets/dokter/';
        $redirect      = '../dokter.php?hapus=success';
        break;

    case 'kasir':
        $tabel         = 'users_212238';
        $kolom_id      = 'id_212238';
        $kolom_gambar  = 'foto_212238';
        $folder_gambar = '../assets/kasir/';
        $redirect      = '../kasir.php?hapus=success';
        break;

    case 'user':
        $tabel         = 'users_212238';
        $kolom_id      = 'id_212238';
        $kolom_gambar  = 'foto_212238';
        $folder_gambar = '../assets/user/';
        $redirect      = '../user.php?hapus=success';
        break;

    default:
        echo "Jenis data tidak dikenali.";
        exit;
}

// Hapus gambar jika ada
$query_gambar = mysqli_query($koneksi, "SELECT $kolom_gambar FROM $tabel WHERE $kolom_id = '$id'");
if ($data = mysqli_fetch_assoc($query_gambar)) {
    $gambar = $data[$kolom_gambar];
    if ($gambar && file_exists($folder_gambar . $gambar)) {
        unlink($folder_gambar . $gambar);
    }
}

// Hapus data dari database
$query_delete = mysqli_query($koneksi, "DELETE FROM $tabel WHERE $kolom_id = '$id'");

if ($query_delete) {
    header("Location: $redirect");
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>
