<?php
include '../../config/koneksi.php';

// Fungsi upload file
function upload_file($tmp, $folder, $filename) {
  if (!is_dir($folder)) mkdir($folder, 0777, true); // buat folder kalau belum ada
  return move_uploaded_file($tmp, $folder . $filename);
}

// ==================== SIMPAN AKSESORIS ====================
if (isset($_POST['simpan_aksesoris'])) {
  $id         = $_POST['id_212238'];
  $nama       = $_POST['nama_aksesoris_212238'];
  $deskripsi  = $_POST['deskripsi_212238'];
  $harga      = $_POST['harga_212238'];
  $stok       = $_POST['stok_212238'];

  $gambar     = $_FILES['gambar_212238']['name'];
  $tmp        = $_FILES['gambar_212238']['tmp_name'];
  $folder     = "../../assets/aksesoris/";

  // Validasi ID unik
  $cek_id = mysqli_query($koneksi, "SELECT id_212238 FROM aksesoris_212238 WHERE id_212238 = '$id'");
  if (mysqli_num_rows($cek_id) > 0) {
    echo "<script>alert('ID aksesoris sudah digunakan, silakan gunakan ID lain.'); window.history.back();</script>";
    exit;
  }

  // Validasi dan upload gambar
  if (is_uploaded_file($tmp)) {
    if (move_uploaded_file($tmp, $folder . $gambar)) {
      $query = "INSERT INTO aksesoris_212238 (
                  id_212238, 
                  nama_aksesoris_212238, 
                  deskripsi_212238, 
                  harga_212238, 
                  stok_212238, 
                  gambar_212238
                ) VALUES (
                  '$id', 
                  '$nama', 
                  '$deskripsi', 
                  '$harga', 
                  '$stok', 
                  '$gambar'
                )";

      if (mysqli_query($koneksi, $query)) {
        header("Location: ../aksesoris.php");
        exit;
      } else {
        echo "Gagal menyimpan data ke database: " . mysqli_error($koneksi);
      }
    } else {
      echo "Gagal memindahkan file gambar ke folder tujuan.";
    }
  } else {
    echo "Tidak ada file gambar yang diunggah.";
  }
}


// ==================== SIMPAN PAKAN ====================
if (isset($_POST['simpan_pakan'])) {
  include '../fungsi/upload_file.php'; // Pastikan fungsi upload_file tersedia
  include '../../config/koneksi.php';

  $id         = uniqid(); // ID manual jika tidak auto_increment
  $nama       = $_POST['nama_pakan_212238'];
  $deskripsi  = $_POST['deskripsi_212238'];
  $harga      = $_POST['harga_212238'];
  $stok       = $_POST['stok_212238'];

  $foto       = $_FILES['foto_212238']['name'];
  $tmp        = $_FILES['foto_212238']['tmp_name'];
  $folder     = "../../assets/pakan/";

  if (upload_file($tmp, $folder, $foto)) {
    $query = "INSERT INTO pakan_212238 
              (id_212238, nama_pakan_212238, deskripsi_212238, harga_212238, stok_212238, foto_212238) 
              VALUES 
              ('$id', '$nama', '$deskripsi', '$harga', '$stok', '$foto')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: ../pakan.php?toast=suskes_tambah");
      exit;
    } else {
      echo "Gagal menambahkan data: " . mysqli_error($koneksi);
    }
  } else {
    echo "Gagal mengupload foto pakan.";
  }
}


// ==================== SIMPAN DOKTER ====================

if (isset($_POST['simpan_dokter'])) {
    $id = $_POST['id_212238'];
    $nama = $_POST['nama_212238'];
    $username = $_POST['username_212238'];
    $password_plain = $_POST['password_212238'];
    $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
    $email = $_POST['email_212238'];
    $telepon = $_POST['telepon_212238'];
    $spesialis = $_POST['spesialis_212238'];
    $no_str = $_POST['no_str_212238'];
    $tanggal_lahir = $_POST['tanggal_lahir_212238'];
    $jenis_kelamin = $_POST['jenis_kelamin_212238'];
    $jadwal_praktek = $_POST['jadwal_praktek_212238'];
    $alamat = $_POST['alamat_212238'];
    $role = $_POST['role_212238'];

    // Proses upload foto
    $foto = $_FILES['foto_212238']['name'];
    $tmp = $_FILES['foto_212238']['tmp_name'];
    $folder     = "../../assets/dokter/";
    $path = $folder . $foto;

    if (move_uploaded_file($tmp, $path)) {
        $sql = "INSERT INTO users_212238 (
            id_212238, nama_212238, username_212238, password_212238, password_plain_212238,
            email_212238, telepon_212238, foto_212238, role_212238,
            spesialis_212238, no_str_212238, tanggal_lahir_212238,
            jenis_kelamin_212238, jadwal_praktek_212238, alamat_212238
        ) VALUES (
            '$id', '$nama', '$username', '$password_hash', '$password_plain',
            '$email', '$telepon', '$foto', '$role',
            '$spesialis', '$no_str', '$tanggal_lahir',
            '$jenis_kelamin', '$jadwal_praktek', '$alamat'
        )";

        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            header("Location: ../dokter.php?status=success");
        } else {
            echo "Gagal menyimpan ke database: " . mysqli_error($koneksi);
        }
    } else {
        echo "Upload foto gagal.";
    }
} else {
    echo "Form belum disubmit dengan benar.";
}


// ==================== SIMPAN KASIR ====================
if (isset($_POST['simpan_kasir'])) {
    $id = $_POST['id_212238'];
    $nama = $_POST['nama_212238'];
    $username = $_POST['username_212238'];
    $password_plain = $_POST['password_212238'];
    $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
    $email = $_POST['email_212238'];
    $telepon = $_POST['telepon_212238'];
    $tanggal_lahir = $_POST['tanggal_lahir_212238'];
    $jenis_kelamin = $_POST['jenis_kelamin_212238'];
    $alamat = $_POST['alamat_212238'];
    $role = $_POST['role_212238'];

    // Proses upload foto
    $foto = $_FILES['foto_212238']['name'];
    $tmp = $_FILES['foto_212238']['tmp_name'];
    $folder     = "../../assets/kasir/";
    $path = $folder . $foto;

    if (move_uploaded_file($tmp, $path)) {
        $sql = "INSERT INTO users_212238 (
            id_212238, nama_212238, username_212238, password_212238, password_plain_212238,
            email_212238, telepon_212238, foto_212238, role_212238, tanggal_lahir_212238,
            jenis_kelamin_212238,  alamat_212238
        ) VALUES (
            '$id', '$nama', '$username', '$password_hash', '$password_plain',
            '$email', '$telepon', '$foto', '$role', '$tanggal_lahir',
            '$jenis_kelamin', '$alamat'
        )";

        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            header("Location: ../kasir.php?status=success");
        } else {
            echo "Gagal menyimpan ke database: " . mysqli_error($koneksi);
        }
    } else {
        echo "Upload foto gagal.";
    }
} else {
    echo "Form belum disubmit dengan benar.";
}




// ==================== SIMPAN USER ====================
if (isset($_POST['simpan_user'])) {
  $nama    = $_POST['nama'];
  $email   = $_POST['email'];
  $telepon = $_POST['telepon'];

  $foto    = $_FILES['foto']['name'];
  $tmp     = $_FILES['foto']['tmp_name'];
  $folder  = "../../assets/user/";

  if (upload_file($tmp, $folder, $foto)) {
    $username = strtolower(str_replace(' ', '', $nama));
    $password = password_hash('123456', PASSWORD_DEFAULT);

    $query = "INSERT INTO users_212238 
              (nama_212238, username_212238, password_212238, email_212238, telepon_212238, foto_212238, role_212238) 
              VALUES ('$nama', '$username', '$password', '$email', '$telepon', '$foto', 'user')";

    if (mysqli_query($koneksi, $query)) {
      header("Location: ../user.php");
    } else {
      echo "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
  } else {
    echo "Gagal mengupload foto user.";
  }
}
?>
