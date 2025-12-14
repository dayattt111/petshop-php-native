<?php
session_start();
include "../config/koneksi.php";
include "header.php";

// Daftar layanan pemeriksaan & perawatan
$services = [
  ['id'=>'mandi',   'title'=>'Mandi & Grooming',   'desc'=>'Perawatan mandi, gunting bulu, pembersihan telinga, dan pemotongan kuku.', 'icon'=>'🛁'],
  ['id'=>'vaksin',  'title'=>'Vaksinasi',          'desc'=>'Pemberian vaksin rabies, distemper, parvovirus, dan vaksin wajib lainnya.',      'icon'=>'💉'],
  ['id'=>'umum',    'title'=>'Pemeriksaan Umum',    'desc'=>'Cek kondisi umum, tekanan, pemeriksaan jantung dan paru.',                    'icon'=>'🔍'],
  ['id'=>'dental',  'title'=>'Dental Cleaning',     'desc'=>'Pembersihan karang gigi dan perawatan mulut.',                                  'icon'=>'🦷'],
  ['id'=>'nutrisi', 'title'=>'Konsultasi Nutrisi',  'desc'=>'Saran makanan & diet khusus untuk hewan peliharaan Anda.',                     'icon'=>'🥗']
];
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-5" id="pemeriksaan">
  <h2 class="text-center mb-4 text-maroon fw-bold">Layanan Pemeriksaan & Perawatan</h2>
  <div class="row g-4">
    <?php foreach ($services as $svc): ?>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100 service-card">
          <div class="card-body text-center">
            <div class="service-icon mb-3" style="font-size:48px;"><?= $svc['icon'] ?></div>
            <h5 class="card-title text-maroon"><?= htmlspecialchars($svc['title']) ?></h5>
            <p class="card-text" style="font-size:14px;color:#555;"><?= nl2br(htmlspecialchars($svc['desc'])) ?></p>
          </div>
          <div class="card-footer bg-white border-0 text-center">
            <button 
              type="button" 
              class="btn btn-maroon w-75" 
              onclick="showLoginPopup()">
              Konsultasi
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Popup Login Script -->
<script>
function showLoginPopup() {
  Swal.fire({
    icon: 'info',
    title: 'Login Diperlukan',
    text: 'Silakan login terlebih dahulu untuk konsultasi layanan ini.',
    confirmButtonColor: '#800000',
    confirmButtonText: 'Login Sekarang',
    showCancelButton: true,
    cancelButtonText: 'Batal'
  }).then((res) => {
    if (res.isConfirmed) {
      window.location.href = '../login.php';
    }
  });
}
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Style tambahan -->
<style>
.text-maroon { color: #800000; }
.btn-maroon {
  background-color: #800000;
  color: #fff;
  border: none;
}
.btn-maroon:hover {
  background-color: #a83232;
}
.service-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.service-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.service-icon { color: #800000; }
</style>
