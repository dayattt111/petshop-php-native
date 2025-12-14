<?php
include "../config/koneksi.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];
$id_user = $_SESSION['user']['id_212238'];

// Pastikan pesanan milik user
$cek = $koneksi->query("SELECT * FROM pesanan_212238 WHERE id_212238 = '$id' AND id_user_212238 = '$id_user'");
if ($cek->num_rows > 0) {
    $koneksi->query("DELETE FROM pesanan_212238 WHERE id_212238 = '$id'");
}

header("Location: pesanan.php");
exit;
?>
