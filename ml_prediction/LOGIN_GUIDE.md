# 🔐 PANDUAN LOGIN & AKSES INTERFACE - XBOXPETSHOP ML SYSTEM

## 📝 LANGKAH-LANGKAH SETUP

### 1️⃣ Import User Accounts
```bash
# Via phpMyAdmin:
# 1. Buka http://localhost/phpmyadmin
# 2. Pilih database "rina212238"
# 3. Tab "SQL"
# 4. Copy-paste isi file ml_user_accounts.sql
# 5. Klik "Kirim" / "Go"

# Via Command Line:
mysql -u root -p rina212238 < ml_user_accounts.sql
```

### 2️⃣ Verifikasi User Berhasil Dibuat
```sql
SELECT id, username, nama_lengkap, level 
FROM user 
WHERE level IN ('admin', 'dokter', 'kasir', 'user');
```

---

## 👥 AKUN LOGIN & AKSES

### 🔑 1. ADMIN
```
Username: admin
Password: admin123
Level:    admin
```

**Halaman yang Bisa Diakses:**
- ✅ **ML Model Management**
  ```
  http://localhost/rina212238/admin/ml_management.php
  ```
  
**Fitur:**
- View model accuracy & performance
- Feature importance analysis
- Retrain model
- Download model & dataset
- Model metadata information
- Training samples count
- Target classes overview

**Screenshot Fitur:**
```
┌─────────────────────────────────────────────┐
│  Model Accuracy: 100%                       │
│  Training Samples: 500                      │
│  Features: 10                               │
│  Target Classes: 3                          │
├─────────────────────────────────────────────┤
│  Feature Importance:                        │
│  ■■■■■■■■■■■■■■■■ 60.68% Hari Sejak Kunjungan│
│  ■■■■■■ 20.15% Ras                          │
│  ■■■ 9.1% Jenis Hewan                       │
└─────────────────────────────────────────────┘
```

---

### ⚕️ 2. DOKTER
```
Username: dokter
Password: dokter123
Level:    dokter
```

**Halaman yang Bisa Diakses:**
- ✅ **AI Medical Assistant**
  ```
  http://localhost/rina212238/dokter/ai_assistant.php
  ```

**Fitur:**
- AI prediction dengan medical context
- Patient information display
- Health check points
- Concern level indicator (High/Medium/Low)
- Alternative diagnosis
- Doctor's validation form
- Medical notes & treatment plan

**Screenshot Fitur:**
```
┌─────────────────────────────────────────────┐
│  Patient: Bella (Kucing Persia)            │
│  Owner: Bapak Ahmad                         │
│  Age: Dewasa (5 tahun)                      │
│  Weight: 6.1 kg                             │
├─────────────────────────────────────────────┤
│  🤖 AI Recommendation: Veterinary           │
│  Confidence: 100% (High)                    │
│  Concern Level: MEDIUM                      │
├─────────────────────────────────────────────┤
│  Check Points:                              │
│  ✓ Kondisi kesehatan umum                   │
│  ✓ Riwayat vaksin                          │
│  ✓ Usia hewan                              │
└─────────────────────────────────────────────┘
```

---

### 💰 3. KASIR
```
Username: kasir
Password: kasir123
Level:    kasir
```

**Halaman yang Bisa Diakses:**
- ✅ **Sales AI Assistant**
  ```
  http://localhost/rina212238/kasir/prediksi_penjualan.php
  ```

**Fitur:**
- Customer check-in display
- AI prediction untuk sales
- Auto-generated sales script
- Upselling opportunities dengan harga
- Promo suggestions
- Alternative services probability
- Process transaction button

**Screenshot Fitur:**
```
┌─────────────────────────────────────────────┐
│  Customer: Ibu Sari                         │
│  Pet: Luna (Kucing Maine Coon)             │
│  Last Visit: 45 hari lalu                   │
├─────────────────────────────────────────────┤
│  🎯 AI Prediction: Grooming (100%)          │
├─────────────────────────────────────────────┤
│  💬 Sales Script:                           │
│  "Berdasarkan riwayat Luna, sepertinya      │
│   sudah waktunya untuk Grooming. Kami       │
│   punya promo spesial hari ini!"            │
├─────────────────────────────────────────────┤
│  ⬆️ Upselling Opportunities:                │
│  ✓ Paket Grooming Premium (+Spa) Rp 200k   │
│  ✓ Parfum Pet Rp 35k                       │
│  ✓ Sisir & Gunting Kuku Rp 50k             │
├─────────────────────────────────────────────┤
│  🎁 Promo: Diskon 20% paket + pakan!       │
└─────────────────────────────────────────────┘
```

---

### 👤 4. USER/PELANGGAN
```
Username: user_pelanggan
Password: user123
Level:    user
```

**Halaman yang Bisa Diakses:**
- ✅ **Service Recommendations**
  ```
  http://localhost/rina212238/user/prediksi_layanan.php
  ```

**Fitur:**
- User-friendly service recommendations
- Beautiful gradient card design
- Confidence score display
- Action buttons (booking/schedule)
- Alternative services dengan progress bar
- Info box tentang AI system

**Screenshot Fitur:**
```
┌─────────────────────────────────────────────┐
│            🐾 Waktunya Grooming!            │
│                                             │
│  Hewan peliharaan Anda mungkin membutuhkan │
│  perawatan grooming. Bulu yang terawat     │
│  membuat hewan lebih sehat dan nyaman.     │
│                                             │
│         ✓ 100% Confidence                  │
│                                             │
│       [Booking Grooming →]                 │
├─────────────────────────────────────────────┤
│  📊 Layanan Lain yang Mungkin Dibutuhkan:  │
│  Grooming      ████████████████ 100%       │
│  Veterinary    ██ 20%                      │
│  Pet Hotel     █ 10%                       │
└─────────────────────────────────────────────┘
```

---

## 🔄 ALUR LOGIN

### Flow Chart:
```
Login Page (login.php)
         ↓
    Input Username & Password
         ↓
    Validasi di Database
         ↓
    ┌─────────────┐
    │ Check Level │
    └─────────────┘
         ↓
    ┌─────┬─────┬─────┬─────┐
    │     │     │     │     │
  Admin Dokter Kasir User  │
    │     │     │     │     │
    ↓     ↓     ↓     ↓     ↓
   ML   AI   Sales  Service
 Mgmt  Med   Asst   Recom
```

---

## 🧪 TESTING WORKFLOW

### Test 1: Login sebagai Admin
```bash
1. Buka: http://localhost/rina212238/login.php
2. Username: admin
3. Password: admin123
4. Klik Login
5. Redirect ke: admin/index.php atau dashboard
6. Akses ML: admin/ml_management.php
```

### Test 2: Login sebagai Dokter
```bash
1. Logout dari admin
2. Login dengan: dokter / dokter123
3. Akses: dokter/ai_assistant.php
4. Lihat AI prediction untuk patient
5. Fill validation form
```

### Test 3: Login sebagai Kasir
```bash
1. Logout
2. Login dengan: kasir / kasir123
3. Akses: kasir/prediksi_penjualan.php
4. Lihat sales script & upselling
5. Test process transaction
```

### Test 4: Login sebagai User
```bash
1. Logout
2. Login dengan: user_pelanggan / user123
3. Akses: user/prediksi_layanan.php
4. Lihat rekomendasi layanan
5. Test booking button
```

---

## 📊 SESSION VARIABLES

Setelah login berhasil, sistem akan set:
```php
$_SESSION['username'] = 'admin';      // Username
$_SESSION['level'] = 'admin';         // Role/Level
$_SESSION['nama_lengkap'] = 'Administrator'; // Full Name
```

### Authentication Check di Setiap Interface:
```php
// Admin
if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'admin')

// Dokter
if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'dokter')

// Kasir
if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'kasir')

// User
if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'user')
```

---

## 🔐 SECURITY NOTES

### ⚠️ DEVELOPMENT MODE (Current)
- Password: **PLAIN TEXT** (tidak dienkripsi)
- Session: Basic PHP session
- SQL Injection: Belum full protected

### ✅ PRODUCTION MODE (Recommended)
1. **Enkripsi Password:**
   ```php
   // Register
   $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
   
   // Login
   if (password_verify($_POST['password'], $user['password']))
   ```

2. **Prepared Statements:**
   ```php
   $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
   $stmt->bind_param("s", $username);
   ```

3. **CSRF Token**
4. **HTTPS Only**
5. **Session Timeout**

---

## 📱 RESPONSIVE DESIGN

Semua interface sudah menggunakan **Bootstrap 5.2.3** dan responsive untuk:
- ✅ Desktop (1920x1080)
- ✅ Tablet (768x1024)
- ✅ Mobile (375x667)

---

## 🐛 TROUBLESHOOTING

### Problem 1: Redirect Loop setelah login
**Solusi:**
```php
// Cek file login.php, pastikan ada:
session_start();
// dan redirect sesuai level
```

### Problem 2: "Model not found" error
**Solusi:**
```bash
php ml_prediction/train_model.php
```

### Problem 3: 404 Not Found
**Solusi:**
- Periksa path file
- Pastikan file exists
- Check .htaccess jika ada

### Problem 4: Session tidak tersimpan
**Solusi:**
```php
// Pastikan session_start() di awal file
session_start();
```

---

## 📞 SUPPORT

Jika ada masalah:
1. Cek error log PHP: `C:\laragon\www\rina212238\error.log`
2. Cek database connection di `config/koneksi.php`
3. Verify user table structure
4. Check file permissions

---

## ✅ CHECKLIST DEPLOYMENT

- [ ] Import SQL users (ml_user_accounts.sql)
- [ ] Train model (php ml_prediction/train_model.php)
- [ ] Test login Admin
- [ ] Test login Dokter
- [ ] Test login Kasir
- [ ] Test login User
- [ ] Verify predictions working
- [ ] Check responsive design
- [ ] Test all buttons & forms

---

**🎉 SISTEM SIAP DIGUNAKAN!**

Login dan explore fitur ML Decision Tree di setiap role!
