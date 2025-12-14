<?php
include "config/koneksi.php";
$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_212238       = $_POST["id_212238"];
    $nama_212238     = $_POST["nama_212238"];
    $username_212238 = $_POST["username_212238"];
    $email_212238    = $_POST["email_212238"];
    $telepon_212238  = $_POST["telepon_212238"];
    $password_212238 = $_POST["password_212238"];
    $role_212238     = "user";

    $password_hash_212238 = password_hash($password_212238, PASSWORD_DEFAULT);

    $foto_212238 = $_FILES["foto_212238"]["name"];
    $tmp_212238  = $_FILES["foto_212238"]["tmp_name"];
    $folder_212238 = "assets/user/";

    if (move_uploaded_file($tmp_212238, $folder_212238 . $foto_212238)) {
        $query_212238 = "INSERT INTO users_212238 
            (id_212238, nama_212238, username_212238, password_212238, password_plain_212238, email_212238, telepon_212238, foto_212238, role_212238) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_212238 = $koneksi->prepare($query_212238);
        $stmt_212238->bind_param("sssssssss", $id_212238, $nama_212238, $username_212238, $password_hash_212238, $password_212238, $email_212238, $telepon_212238, $foto_212238, $role_212238);

        if ($stmt_212238->execute()) {
            header("Location: register.php?success=1");
            exit();
        } else {
            $error = "Gagal mendaftar: " . $stmt_212238->error;
        }
    } else {
        $error = "Upload foto gagal.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Daftar - PetShop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
  <div class="card p-4 shadow" style="width: 400px;">
    <h4 class="mb-3 text-center text-danger">Daftar Akun User</h4>
    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-2">
        <input type="text" name="id_212238" class="form-control" placeholder="ID User (misal: USR001)" required />
      </div>
      <div class="mb-2">
        <input type="text" name="nama_212238" class="form-control" placeholder="Nama Lengkap" required />
      </div>
      <div class="mb-2">
        <input type="text" name="username_212238" class="form-control" placeholder="Username" required />
      </div>
      <div class="mb-2">
        <input type="email" name="email_212238" class="form-control" placeholder="Email" required />
      </div>
      <div class="mb-2">
        <input type="text" name="telepon_212238" class="form-control" placeholder="No. Telepon" required />
      </div>
      <div class="mb-2">
        <input type="password" name="password_212238" class="form-control" placeholder="Password" required />
      </div>
      <div class="mb-3">
        <input type="file" name="foto_212238" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-danger w-100">Daftar</button>
      <div class="mt-3 text-center">
        <a href="login.php">Kembali ke Login</a>
      </div>
    </form>
  </div>

  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
      Swal.fire({
        title: 'Berhasil!',
        text: 'Akun berhasil didaftarkan.',
        icon: 'success',
        confirmButtonText: 'Lanjut ke Login',
        confirmButtonColor: '#d33'
      }).then(() => {
        window.location.href = 'login.php';
      });
    </script>
  <?php endif; ?>
</body>
</html>
