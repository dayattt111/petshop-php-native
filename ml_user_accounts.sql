-- ============================================================================
-- XBOXPETSHOP - USER ACCOUNTS SQL
-- Insert user untuk setiap role: Admin, Dokter, Kasir, User
-- Password TIDAK dienkripsi (plain text) untuk development/testing
-- ============================================================================

-- Hapus data lama jika ada (optional)
-- DELETE FROM user WHERE username IN ('admin', 'dokter', 'kasir', 'user_pelanggan');

-- ============================================================================
-- 1. ADMIN ACCOUNT
-- ============================================================================
INSERT INTO user (username, password, nama_lengkap, level) 
VALUES ('admin', 'admin123', 'Administrator Xboxpetshop', 'admin');

-- ============================================================================
-- 2. DOKTER ACCOUNT
-- ============================================================================
INSERT INTO user (username, password, nama_lengkap, level) 
VALUES ('dokter', 'dokter123', 'Dr. Ahmad Veterinarian', 'dokter');

-- ============================================================================
-- 3. KASIR ACCOUNT
-- ============================================================================
INSERT INTO user (username, password, nama_lengkap, level) 
VALUES ('kasir', 'kasir123', 'Sari Kasir Petshop', 'kasir');

-- ============================================================================
-- 4. USER/PELANGGAN ACCOUNT
-- ============================================================================
INSERT INTO user (username, password, nama_lengkap, level) 
VALUES ('user_pelanggan', 'user123', 'Budi Pelanggan', 'user');

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
│    Username: admin                                                  │
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
│    Username: dokter                                                 │
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
│    Username: kasir                                                  │
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
│    Username: user_pelanggan                                         │
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
