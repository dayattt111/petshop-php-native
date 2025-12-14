<?php
session_start();
session_unset(); // Menghapus semua variabel session
session_destroy(); // Mengakhiri session
header("Location: index.php"); // Ganti path jika login bukan di atas folder ini
exit;
?>
