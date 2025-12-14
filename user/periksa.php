<?php
include "../config/koneksi.php";
include "header.php";

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role_212238'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['user']['id_212238'];

$services = [
  ['id'=>'mandi',   'title'=>'Mandi & Grooming',   'desc'=>'Perawatan mandi, gunting bulu, telinga, kuku.', 'icon'=>'🛁'],
  ['id'=>'vaksin',  'title'=>'Vaksinasi',          'desc'=>'Vaksin rabies, distemper, parvovirus, dll.', 'icon'=>'💉'],
  ['id'=>'umum',    'title'=>'Pemeriksaan Umum',   'desc'=>'Cek kondisi umum, jantung, paru-paru.', 'icon'=>'🔍'],
  ['id'=>'dental',  'title'=>'Dental Cleaning',    'desc'=>'Pembersihan karang gigi & mulut.', 'icon'=>'🦷'],
  ['id'=>'nutrisi', 'title'=>'Konsultasi Nutrisi', 'desc'=>'Saran diet & nutrisi hewan.', 'icon'=>'🥗'],
];

// Tangani form kirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jenis = $_POST['jenis'] ?? '';
    $keluhan = $_POST['keluhan'] ?? '';
    $tgl = $_POST['tgl'] ?? date('Y-m-d');

    if (!empty($jenis) && !empty($keluhan)) {
        $id = uniqid('periksa_');
        $query = "INSERT INTO pemeriksaan_212238 
                  (id_212238, id_user_212238, keluhan_212238, tgl_212238, status_212238)
                  VALUES ('$id', '$id_user', '$keluhan', '$tgl', 'baru')";
        if ($koneksi->query($query)) {
            echo "<script>
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Konsultasi berhasil dikirim.',
                confirmButtonColor: '#800000'
              }).then(() => window.location='periksa.php');
            </script>";
            exit;
        } else {
            echo "<script>
              Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan data.',
                confirmButtonColor: '#800000'
              });
            </script>";
        }
    }
}
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-5">
  <h2 class="text-center mb-4 fw-bold text-maroon">Layanan Pemeriksaan & Konsultasi</h2>
  <div class="row g-4">
    <?php foreach ($services as $svc): ?>
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card border-0 shadow-sm h-100 service-card">
        <div class="card-body text-center">
          <div class="icon mb-3" style="font-size: 42px;"><?= $svc['icon'] ?></div>
          <h5 class="card-title text-maroon"><?= $svc['title'] ?></h5>
          <p class="card-text small"><?= $svc['desc'] ?></p>
        </div>
        <div class="card-footer bg-white border-0 text-center">
          <button class="btn btn-yellow w-75" onclick="bukaForm('<?= $svc['title'] ?>')">
            Konsultasi
          </button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Popup Form Konsultasi -->
<script>
function bukaForm(jenis) {
  Swal.fire({
    title: 'Konsultasi: ' + jenis,
    html: `
      <form id="formKonsultasi" class="text-start">
        <input type="hidden" name="jenis" value="${jenis}">
        <div class="mb-3">
          <label class="form-label fw-bold">Tanggal</label>
          <input type="date" name="tgl" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Keluhan</label>
          <textarea name="keluhan" class="form-control" placeholder="Tulis keluhan hewan Anda..." required></textarea>
        </div>
      </form>
    `,
    confirmButtonText: 'Kirim',
    confirmButtonColor: '#800000',
    showCancelButton: true,
    cancelButtonText: 'Batal',
    preConfirm: () => {
      const form = Swal.getPopup().querySelector('#formKonsultasi');
      const formData = new FormData(form);

      return fetch('', {
        method: 'POST',
        body: formData
      }).then(response => {
        if (!response.ok) throw new Error(response.statusText);
        return true;
      }).catch(error => {
        Swal.showValidationMessage(`Gagal mengirim: ${error}`);
      });
    }
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        icon: 'success',
        title: 'Terkirim!',
        text: 'Konsultasi berhasil dikirim.',
        confirmButtonColor: '#800000'
      }).then(() => {
        window.location.reload();
      });
    }
  });
}
</script>

<!-- Styling -->
<style>
.text-maroon { color: #800000; }
.btn-yellow {
  background-color: #FFD700;
  color: #800000;
  border: none;
}
.btn-yellow:hover {
  background-color: #FFC107;
  color: #fff;
}
.service-card {
  transition: all 0.3s ease;
}
.service-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
}
.icon {
  color: #800000;
}
</style>
