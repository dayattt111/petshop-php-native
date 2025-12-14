<?php
include "header.php";
?>

<div class="container py-5">
  <h2 class="text-center text-maroon fw-bold mb-4">Tentang Kami</h2>
  <p class="text-center mb-5" style="max-width:700px;margin:auto;">
    Selamat datang di <strong>Petshop Kita</strong> – tempat terbaik untuk memenuhi segala kebutuhan hewan peliharaan Anda. 
    Kami hadir sejak tahun 2022 di Bone, Sulawesi Selatan dengan komitmen untuk memberikan pelayanan terbaik melalui produk berkualitas dan layanan profesional.
  </p>

  <!-- Alamat & Peta -->
  <div class="row justify-content-center mb-5">
    <div class="col-md-6 text-center">
      <h5 class="text-maroon fw-bold">Alamat Kami</h5>
      <p>
        Jl. Sultan Hasanuddin No.123<br>
        Bone, Sulawesi Selatan<br>
        Telp: 0821-3456-7890
      </p>
      <div class="ratio ratio-4x3 shadow-sm rounded overflow-hidden">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126877.28976151025!2d119.597918!3d-4.536352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbe9b876d0e4c29%3A0x9a9a7744b5776907!2sBone%2C%20Sulawesi%20Selatan!5e0!3m2!1sid!2sid!4v1718553400000!5m2!1sid!2sid" 
          width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </div>
  </div>

  <!-- Visi & Misi -->
  <div class="row justify-content-center mb-5">
    <div class="col-md-8">
      <h5 class="text-maroon fw-bold">Visi</h5>
      <p>
        Menjadi petshop terbaik dan terpercaya di Sulawesi Selatan dalam memberikan layanan menyeluruh bagi hewan peliharaan.
      </p>
      <h5 class="text-maroon fw-bold">Misi</h5>
      <ul>
        <li>Menyediakan produk berkualitas dan aman untuk hewan peliharaan.</li>
        <li>Memberikan layanan pemeriksaan hewan oleh dokter berpengalaman.</li>
        <li>Mendidik pemilik hewan mengenai perawatan dan kesejahteraan hewan.</li>
        <li>Memberikan pengalaman belanja yang mudah dan menyenangkan.</li>
      </ul>
    </div>
  </div>

  <!-- Tim -->
  <div class="text-center mb-5">
    <h5 class="text-maroon fw-bold">Tim Kami</h5>
    <img src="../assets/tim.jpg" class="img-fluid rounded shadow" style="max-height:300px;" alt="Tim Petshop">
    <p class="mt-2">Tim dokter hewan & pet care specialist kami siap membantu Anda dengan sepenuh hati.</p>
  </div>
</div>

<!-- Tambahan Style -->
<style>
.text-maroon {
  color: #800000;
}
</style>

<?php include "footer.php"; ?>
