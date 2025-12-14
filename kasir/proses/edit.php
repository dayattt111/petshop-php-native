<?php
include '../../config/koneksi.php';

// ==================== UPDATE AKSESORIS ====================
if (isset($_POST['update_aksesoris'])) {
    $id     = $_POST['id_212238'];
    $nama   = $_POST['nama_aksesoris_212238'];
    $deskripsi = $_POST['deskripsi_212238'];
    $harga  = $_POST['harga_212238'];
    $stok   = $_POST['stok_212238'];

    $folder = "../../assets/aksesoris/";
    $gambar = $_FILES['gambar_212238']['name'];
    $tmp    = $_FILES['gambar_212238']['tmp_name'];

    if (!empty($gambar)) {
        if (upload_file($tmp, $folder, $gambar)) {
            $query = "UPDATE aksesoris_212238 SET 
                        nama_aksesoris_212238='$nama', 
                        deskripsi_212238='$deskripsi', 
                        harga_212238='$harga', 
                        stok_212238='$stok', 
                        gambar_212238='$gambar' 
                      WHERE id_212238='$id'";
        } else {
            echo "Gagal mengupload gambar.";
            exit;
        }
    } else {
        $query = "UPDATE aksesoris_212238 SET 
                    nama_aksesoris_212238='$nama', 
                    deskripsi_212238='$deskripsi', 
                    harga_212238='$harga', 
                    stok_212238='$stok' 
                  WHERE id_212238='$id'";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: ../aksesoris.php?update=success");
    } else {
        echo "Gagal update aksesoris: " . mysqli_error($koneksi);
    }
}


// ==================== UPDATE PAKAN ====================
if (isset($_POST['update_pakan'])) {
    $id         = $_POST['id_212238'];
    $nama       = $_POST['nama_pakan_212238'];
    $deskripsi  = $_POST['deskripsi_212238'];
    $harga      = $_POST['harga_212238'];
    $stok       = $_POST['stok_212238'];
    $folder     = "../../assets/pakan/";

    $gambar = $_FILES['foto_212238']['name'];
    $tmp    = $_FILES['foto_212238']['tmp_name'];

    if (!empty($gambar)) {
        if (upload_file($tmp, $folder, $gambar)) {
            $query = "UPDATE pakan_212238 SET 
                        nama_pakan_212238 = '$nama',
                        deskripsi_212238 = '$deskripsi',
                        harga_212238 = '$harga',
                        stok_212238 = '$stok',
                        foto_212238 = '$gambar'
                      WHERE id_212238 = '$id'";
        } else {
            echo "Gagal mengupload gambar.";
            exit;
        }
    } else {
        $query = "UPDATE pakan_212238 SET 
                    nama_pakan_212238 = '$nama',
                    deskripsi_212238 = '$deskripsi',
                    harga_212238 = '$harga',
                    stok_212238 = '$stok'
                  WHERE id_212238 = '$id'";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: ../pakan.php?toast=sukses_edit");
    } else {
        echo "Gagal mengupdate data pakan: " . mysqli_error($koneksi);
    }
}


// ==================== UPDATE DOKTER ====================
if (isset($_POST['update_dokter'])) {
    $id             = $_POST['id_212238'];
    $nama           = $_POST['nama_212238'];
    $username       = $_POST['username_212238'];
    $email          = $_POST['email_212238'];
    $telepon        = $_POST['telepon_212238'];
    $spesialis      = $_POST['spesialis_212238'];
    $no_str         = $_POST['no_str_212238'];
    $tanggal_lahir  = $_POST['tanggal_lahir_212238'];
    $jenis_kelamin  = $_POST['jenis_kelamin_212238'];
    $jadwal_praktek = $_POST['jadwal_praktek_212238'];
    $alamat         = $_POST['alamat_212238'];
    $folder         = "../../assets/dokter/";

    $foto_baru = $_FILES['foto_212238']['name'];
    $tmp       = $_FILES['foto_212238']['tmp_name'];

    $query_old = mysqli_query($koneksi, "SELECT foto_212238 FROM users_212238 WHERE id_212238 = '$id'");
    $foto_lama = mysqli_fetch_assoc($query_old)['foto_212238'];

    if (!empty($foto_baru)) {
        if (upload_file($tmp, $folder, $foto_baru)) {
            $foto = $foto_baru;
        } else {
            echo "Upload foto gagal.";
            exit;
        }
    } else {
        $foto = $foto_lama;
    }

    $sql = "UPDATE users_212238 SET
                nama_212238 = '$nama',
                username_212238 = '$username',
                email_212238 = '$email',
                telepon_212238 = '$telepon',
                foto_212238 = '$foto',
                role_212238 = 'dokter',
                spesialis_212238 = '$spesialis',
                no_str_212238 = '$no_str',
                tanggal_lahir_212238 = '$tanggal_lahir',
                jenis_kelamin_212238 = '$jenis_kelamin',
                jadwal_praktek_212238 = '$jadwal_praktek',
                alamat_212238 = '$alamat'
            WHERE id_212238 = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: ../dokter.php?update=success");
    } else {
        echo "Gagal update data dokter: " . mysqli_error($koneksi);
    }
}

// ==================== UPDATE KASIR ====================
if (isset($_POST['update_kasir'])) {
    $id             = $_POST['id_212238'];
    $nama           = $_POST['nama_212238'];
    $username       = $_POST['username_212238'];
    $email          = $_POST['email_212238'];
    $telepon        = $_POST['telepon_212238'];
    $tanggal_lahir  = $_POST['tanggal_lahir_212238'];
    $jenis_kelamin  = $_POST['jenis_kelamin_212238'];
    $alamat         = $_POST['alamat_212238'];
    $folder         = "../../assets/kasir/";

    $foto_baru = $_FILES['foto_212238']['name'];
    $tmp       = $_FILES['foto_212238']['tmp_name'];

    $query_old = mysqli_query($koneksi, "SELECT foto_212238 FROM users_212238 WHERE id_212238 = '$id'");
    $foto_lama = mysqli_fetch_assoc($query_old)['foto_212238'];

    if (!empty($foto_baru)) {
        if (upload_file($tmp, $folder, $foto_baru)) {
            $foto = $foto_baru;
        } else {
            echo "Upload foto gagal.";
            exit;
        }
    } else {
        $foto = $foto_lama;
    }

    $sql = "UPDATE users_212238 SET
                nama_212238 = '$nama',
                username_212238 = '$username',
                email_212238 = '$email',
                telepon_212238 = '$telepon',
                foto_212238 = '$foto',
                role_212238 = 'kasir',
                tanggal_lahir_212238 = '$tanggal_lahir',
                jenis_kelamin_212238 = '$jenis_kelamin',
                alamat_212238 = '$alamat'
            WHERE id_212238 = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: ../kasir.php?update=success");
    } else {
        echo "Gagal update data kasir: " . mysqli_error($koneksi);
    }
}
?>
