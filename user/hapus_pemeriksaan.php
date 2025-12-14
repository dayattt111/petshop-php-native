<?php
include "../config/koneksi.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start(); // Untuk menghindari header error
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Pemeriksaan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
if (!isset($_GET['id'])) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'ID tidak ditemukan',
            text: 'Permintaan tidak valid.',
            confirmButtonColor: '#800000'
        }).then(() => {
            window.location.href = 'data_periksa.php';
        });
    </script>";
    exit;
}

$id = $_GET['id'];
$hapus = $koneksi->query("DELETE FROM pemeriksaan_212238 WHERE id_212238 = '$id'");

if ($hapus) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data pemeriksaan berhasil dihapus.',
            confirmButtonColor: '#800000'
        }).then(() => {
            window.location.href = 'data_periksa.php';
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Data gagal dihapus. Silakan coba lagi.',
            confirmButtonColor: '#800000'
        }).then(() => {
            window.location.href = 'data_periksa.php';
        });
    </script>";
}
?>

</body>
</html>
