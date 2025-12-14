<?php
session_start();
include "config/koneksi.php";

$error = ""; // Untuk pesan error
$success = ""; // Untuk pesan sukses

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_212238 = trim($_POST["username_212238"]);
    $password_212238 = trim($_POST["password_212238"]);
    $role_212238     = trim($_POST["role_212238"]);

    $stmt = $koneksi->prepare("SELECT * FROM users_212238 WHERE username_212238 = ? AND role_212238 = ?");
    $stmt->bind_param("ss", $username_212238, $role_212238);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $user = $result->fetch_assoc()) {
        $password_db_212238 = $user["password_212238"];
        $is_valid_212238 = false;

        // Jika admin, cocokkan langsung (karena plain text); lainnya gunakan password_verify
        if ($role_212238 === "admin") {
            $is_valid_212238 = ($password_212238 === $password_db_212238);
        } else {
            $is_valid_212238 = password_verify($password_212238, $password_db_212238);
        }

        if ($is_valid_212238) {
            $_SESSION["user"] = $user;
            $_SESSION["id_212238"] = $user["id_212238"];
            $_SESSION["username_212238"] = $user["username_212238"];
            $_SESSION["nama_212238"] = $user["nama_212238"];
            $_SESSION["role_212238"] = $user["role_212238"];
            $_SESSION["email_212238"] = $user["email_212238"];
            $success = "Login berhasil sebagai $role_212238";

            // Redirect berdasarkan role
            switch ($role_212238) {
                case "admin":
                    header("Location: admin/index.php");
                    break;
                case "dokter":
                    header("Location: dokter/index.php");
                    break;
                case "kasir":
                    header("Location: kasir/index.php");
                    break;
                case "user":
                    header("Location: user/index.php");
                    break;
                default:
                    session_destroy();
                    header("Location: login.php?error=Role tidak dikenali");
                    break;
            }
            exit(); // Pindahkan exit() di luar switch agar tidak redundan
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Login gagal! Username, password, atau role salah.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - PetShop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <style>
        body {
            background: linear-gradient(to right, #ff9966, #ff5e62);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fade-in 0.8s ease-in-out;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 350px;
            animation: fade-in 0.8s ease-in-out;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .btn-login {
            background-color: #ff5e62;
            border: none;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: #ff3b41;
        }
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <a href="index.php"
            class="btn btn-outline-light w-100 mb-3 d-flex align-items-center justify-content-center">
            <i class="bi bi-house-door-fill me-2"></i>Beranda
        </a>
        <?php if ($error) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username_212238" class="form-control" placeholder="Enter username" required />
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password_212238" class="form-control" placeholder="Enter password" required />
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role_212238" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                    <option value="dokter">Dokter</option>
                    <option value="user">User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-login w-100 text-white">Login</button>
            <div class="mt-3 text-center">
                <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            </div>
        </form>
    </div>
</body>
</html>
