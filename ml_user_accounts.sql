-- ============================================================================
-- XBOXPETSHOP - USER ACCOUNTS SQL
-- Insert user untuk setiap role: Admin, Dokter, Kasir, User
-- Menggunakan table users_212238 dengan struktur lengkap
-- ============================================================================

-- Hapus data lama jika ada
DELETE FROM users_212238 WHERE id_212238 IN ('ML_ADMIN', 'ML_DOKTER', 'ML_KASIR', 'ML_USER');

-- ============================================================================
-- 1. ADMIN ACCOUNT
-- ============================================================================
INSERT INTO users_212238 (id_212238, nama_212238, username_212238, password_212238, password_plain_212238, email_212238, telepon_212238, foto_212238, role_212238) 
VALUES ('ML_ADMIN', 'Administrator ML Xboxpetshop', 'admin_ml', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin123', 'admin_ml@xboxpetshop.com', '08111111111', 'default.png', 'admin');

-- ============================================================================
-- 2. DOKTER ACCOUNT
-- ============================================================================
INSERT INTO users_212238 (id_212238, nama_212238, username_212238, password_212238, password_plain_212238, email_212238, telepon_212238, foto_212238, role_212238) 
VALUES ('ML_DOKTER', 'Dr. Ahmad ML Veterinarian', 'dokter_ml', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dokter123', 'dokter_ml@xboxpetshop.com', '08222222222', 'default.png', 'dokter');

-- ============================================================================
-- 3. KASIR ACCOUNT
-- ============================================================================
INSERT INTO users_212238 (id_212238, nama_212238, username_212238, password_212238, password_plain_212238, email_212238, telepon_212238, foto_212238, role_212238) 
VALUES ('ML_KASIR', 'Sari Kasir ML Petshop', 'kasir_ml', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kasir123', 'kasir_ml@xboxpetshop.com', '08333333333', 'default.png', 'kasir');

-- ============================================================================
-- 4. USER/PELANGGAN ACCOUNT
-- ============================================================================
INSERT INTO users_212238 (id_212238, nama_212238, username_212238, password_212238, password_plain_212238, email_212238, telepon_212238, foto_212238, role_212238) 
VALUES ('ML_USER', 'Budi Pelanggan ML', 'user_ml', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user123', 'user_ml@xboxpetshop.com', '08444444444', 'default.png', 'user');

-- ============================================================================
-- ALTERNATIF: Jika tabel user memiliki field tambahan
-- ============================================================================

-- Jika ada field email, no_telp, alamat, dll:
/*
INSERT INTO user (username, password, nama_lengkap, email, no_telp, alamat, level, created_at) 
VALUES 
('admin', 'admin123', 'Administrator Xboxpetshop', 'admin@xboxpetshop.com', '081234567890', 'Makassar', 'admin', NOW()),
('dokter', 'dokter123', 'Dr. Ahmad Veterinarian', 'dokter@xboxpetshop.com', '081234567891', 'Makassar', 'dokter', NOW()),
('kasir', 'kasir123', 'Sari Kasir Petshop', 'kasir@xboxpetshop.com', '081234567892', 'Makassar', 'kasir', NOW()),
('user_pelanggan', 'user123', 'Budi Pelanggan', 'budi@email.com', '081234567893', 'Makassar', 'user', NOW());
*/

-- ============================================================================
-- VERIFIKASI DATA
-- ============================================================================
SELECT id, username, nama_lengkap, level 
FROM user 
WHERE level IN ('admin', 'dokter', 'kasir', 'user')
ORDER BY 
    CASE level 
        WHEN 'admin' THEN 1 
        WHEN 'dokter' THEN 2 
        WHEN 'kasir' THEN 3 
        WHEN 'user' THEN 4 
    END;

-- ============================================================================
-- INFORMASI LOGIN
-- ============================================================================

/*
╔═══════════════════════════════════════════════════════════════════════╗
║                      XBOXPETSHOP - LOGIN CREDENTIALS                  ║
╚═══════════════════════════════════════════════════════════════════════╝

┌─────────────────────────────────────────────────────────────────────┐
│ 1. ADMIN                                                            │
├─────────────────────────────────────────────────────────────────────┤
│    Username: admin_ml                                               │
│    Password: admin123                                               │
│    Access:   - ML Model Management                                  │
│              - View Accuracy & Feature Importance                   │
│              - Retrain Model                                        │
│              - User Management                                      │
│    URL:      http://localhost/rina212238/admin/ml_management.php    │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│ 2. DOKTER                                                           │
├─────────────────────────────────────────────────────────────────────┤
│    Username: dokter_ml                                              │
│    Password: dokter123                                              │
│    Access:   - AI Medical Assistant                                 │
│              - Validate AI Predictions                              │
│              - Medical Check Points                                 │
│              - Patient History                                      │
│    URL:      http://localhost/rina212238/dokter/ai_assistant.php    │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│ 3. KASIR                                                            │
├─────────────────────────────────────────────────────────────────────┤
│    Username: kasir_ml                                               │
│    Password: kasir123                                               │
│    Access:   - Sales AI Assistant                                   │
│              - Upselling Opportunities                              │
│              - Sales Script Generator                               │
│              - Promo Suggestions                                    │
│    URL:      http://localhost/rina212238/kasir/prediksi_penjualan.php│
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│ 4. USER/PELANGGAN                                                   │
├─────────────────────────────────────────────────────────────────────┤
│    Username: user_ml                                                │
│    Password: user123                                                │
│    Access:   - Service Recommendations                              │
│              - Pet Care Reminders                                   │
│              - Booking Services                                     │
│              - View Predictions                                     │
│    URL:      http://localhost/rina212238/user/prediksi_layanan.php  │
└─────────────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════════

CATATAN PENTING:
1. Password di atas adalah PLAIN TEXT (tidak dienkripsi)
2. Untuk production, gunakan password yang di-hash (MD5, bcrypt, dll)
3. Ganti password default setelah first login
4. Backup database sebelum import

═══════════════════════════════════════════════════════════════════════

CARA IMPORT:
1. Buka phpMyAdmin
2. Pilih database: rina212238
3. Klik tab "SQL"
4. Copy-paste SQL di atas
5. Klik "Go" / "Kirim"

ATAU via Command Line:
mysql -u root -p rina212238 < ml_user_accounts.sql

═══════════════════════════════════════════════════════════════════════
*/
