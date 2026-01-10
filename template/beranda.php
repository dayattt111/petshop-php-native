<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #800000 0%, #dc143c 100%); color: white; padding: 80px 0;">
  <div class="container text-center">
    <div class="row align-items-center">
      <div class="col-lg-6 text-lg-start mb-4 mb-lg-0">
        <h1 class="display-4 fw-bold mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="d-inline-block me-2" viewBox="0 0 16 16">
            <path d="M8 5.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zM2.5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM8 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
          </svg>
          PetShop NTI
        </h1>
        <h2 class="mb-4">Sahabat Terbaik Hewan Kesayangan Anda</h2>
        <p class="lead mb-4">Temukan berbagai kebutuhan hewan peliharaan Anda dengan produk berkualitas dan layanan kesehatan profesional.</p>
        <div class="d-flex gap-3 justify-content-lg-start justify-content-center">
          <a href="<?= BASE_URL ?>login.php" class="btn btn-light btn-lg px-4">Mulai Belanja</a>
          <a href="<?= BASE_URL ?>template/tentang.php" class="btn btn-outline-light btn-lg px-4">Tentang Kami</a>
        </div>
      </div>
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=600&h=400&fit=crop" class="img-fluid rounded shadow-lg" alt="Happy Pets" style="max-height: 400px; width: 100%; object-fit: cover;">
      </div>
    </div>
  </div>
</section>

<!-- Statistics Section -->
<section class="py-5" style="background-color: #f8f9fa;">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-md-3 col-sm-6">
        <div class="p-3">
          <h2 class="fw-bold" style="color: #800000;">5000+</h2>
          <p class="text-muted mb-0">Pelanggan Setia</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="p-3">
          <h2 class="fw-bold" style="color: #800000;">500+</h2>
          <p class="text-muted mb-0">Produk Tersedia</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="p-3">
          <h2 class="fw-bold" style="color: #800000;">10+</h2>
          <p class="text-muted mb-0">Dokter Hewan</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="p-3">
          <h2 class="fw-bold" style="color: #800000;">4.9/5</h2>
          <p class="text-muted mb-0">Rating Pelanggan</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white">
  <div class="container">
    <h3 class="text-center fw-bold mb-2" style="color: #800000;">Layanan Kami</h3>
    <p class="text-center text-muted mb-5">Solusi lengkap untuk kebutuhan hewan peliharaan Anda</p>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm p-4">
          <div class="card-body">
            <h5 class="card-title fw-bold mb-3">Produk Berkualitas</h5>
            <p class="card-text text-muted">Pakan premium, aksesoris lengkap, dan produk perawatan hewan terbaik.</p>
            <ul class="list-unstyled mt-3">
              <li class="mb-2">Pakan Impor & Lokal</li>
              <li class="mb-2">Aksesoris Lengkap</li>
              <li class="mb-2">Vitamin & Suplemen</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm p-4">
          <div class="card-body">
            <h5 class="card-title fw-bold mb-3">Pemeriksaan Kesehatan</h5>
            <p class="card-text text-muted">Layanan pemeriksaan hewan oleh dokter berpengalaman.</p>
            <ul class="list-unstyled mt-3">
              <li class="mb-2">Konsultasi Kesehatan</li>
              <li class="mb-2">Vaksinasi Lengkap</li>
              <li class="mb-2">Perawatan Rutin</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm p-4">
          <div class="card-body">
            <h5 class="card-title fw-bold mb-3">Sistem AI Prediksi</h5>
            <p class="card-text text-muted">Teknologi untuk prediksi layanan dan rekomendasi produk.</p>
            <ul class="list-unstyled mt-3">
              <li class="mb-2">Prediksi Kebutuhan Layanan</li>
              <li class="mb-2">Rekomendasi Produk</li>
              <li class="mb-2">Analisis Data Penjualan</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Product Categories -->
<section class="py-5" style="background-color: #f8f9fa;">
  <div class="container">
    <h3 class="text-center fw-bold mb-2" style="color: #800000;">Kategori Produk</h3>
    <p class="text-center text-muted mb-5">Jelajahi berbagai kategori produk berkualitas</p>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm overflow-hidden">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=300&h=200&fit=crop" class="img-fluid h-100" alt="Aksesoris" style="object-fit: cover;">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title fw-bold" style="color: #800000;">Aksesoris Hewan</h5>
                <p class="card-text">Kalung, tali, kandang, mainan, dan berbagai aksesoris untuk hewan peliharaan Anda.</p>
                <a href="<?= BASE_URL ?>template/aksesoris.php" class="btn btn-sm" style="background-color: #800000; color: white;">Lihat Produk</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 shadow-sm overflow-hidden">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="https://images.unsplash.com/photo-1600804931749-2da4ce26c869?w=300&h=200&fit=crop" class="img-fluid h-100" alt="Pakan" style="object-fit: cover;">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title fw-bold" style="color: #800000;">Pakan & Makanan</h5>
                <p class="card-text">Pakan berkualitas dari berbagai merek terpercaya untuk nutrisi optimal hewan Anda.</p>
                <a href="<?= BASE_URL ?>template/pakan.php" class="btn btn-sm" style="background-color: #800000; color: white;">Lihat Produk</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="py-5 bg-white">
  <div class="container">
    <h3 class="text-center fw-bold mb-2" style="color: #800000;">Mengapa Memilih PetShop NTI</h3>
    <p class="text-center text-muted mb-5">Kepercayaan pelanggan adalah prioritas kami</p>
    <div class="row g-4">
      <div class="col-md-3 col-sm-6">
        <div class="p-3 text-center">
          <h6 class="fw-bold">Produk Original</h6>
          <p class="text-muted small">100% produk asli dan bergaransi</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="p-3 text-center">
          <h6 class="fw-bold">Pengiriman Cepat</h6>
          <p class="text-muted small">Pengiriman aman dan tepat waktu</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="p-3 text-center">
          <h6 class="fw-bold">Pembayaran Mudah</h6>
          <p class="text-muted small">Berbagai metode pembayaran</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="p-3 text-center">
          <h6 class="fw-bold">Promo Menarik</h6>
          <p class="text-muted small">Diskon dan hadiah setiap bulan</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="py-5" style="background-color: #f8f9fa;">
  <div class="container">
    <h3 class="text-center fw-bold mb-2" style="color: #800000;">Testimoni Pelanggan</h3>
    <p class="text-center text-muted mb-5">Apa kata pelanggan tentang layanan kami</p>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="mb-3">
              <span style="color: #ffc107;">★★★★★</span>
            </div>
            <p class="card-text">Pelayanan sangat memuaskan. Produk berkualitas dan dokter hewannya berpengalaman. Kucing saya jadi lebih sehat.</p>
            <div class="d-flex align-items-center mt-3">
              <div class="rounded-circle bg-secondary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: white;">AS</div>
              <div class="ms-3">
                <h6 class="mb-0 fw-bold">Andi Setiawan</h6>
                <small class="text-muted">Pemilik Kucing Persia</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="mb-3">
              <span style="color: #ffc107;">★★★★★</span>
            </div>
            <p class="card-text">Pakan yang dijual sangat lengkap dan harganya kompetitif. Sistem AI prediksi layanan juga membantu.</p>
            <div class="d-flex align-items-center mt-3">
              <div class="rounded-circle bg-secondary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: white;">SP</div>
              <div class="ms-3">
                <h6 class="mb-0 fw-bold">Siti Putri</h6>
                <small class="text-muted">Pemilik Anjing Golden</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="mb-3">
              <span style="color: #ffc107;">★★★★★</span>
            </div>
            <p class="card-text">Sudah 2 tahun jadi pelanggan setia. Aksesoris dan mainan untuk kelinci saya selalu beli di sini.</p>
            <div class="d-flex align-items-center mt-3">
              <div class="rounded-circle bg-secondary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: white;">RH</div>
              <div class="ms-3">
                <h6 class="mb-0 fw-bold">Rizki Hidayat</h6>
                <small class="text-muted">Pemilik Kelinci</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, #800000 0%, #dc143c 100%); color: white;">
  <div class="container text-center">
    <h3 class="fw-bold mb-3">Mulai Belanja Sekarang</h3>
    <p class="lead mb-4">Daftar sekarang dan dapatkan berbagai keuntungan menjadi member PetShop NTI.</p>
    <a href="<?= BASE_URL ?>register.php" class="btn btn-light btn-lg px-5">Daftar Sekarang</a>
  </div>
</section>
