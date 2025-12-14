# FIELD MAPPING - DATASET MAKASSAR 2024

## Dataset Original vs Processed

### Dataset Original (Dataset_Hewan_Petshop_Makassar_2024.csv)
```
No,Jenis Hewan,Ras,Berat Badan (Kg),Usia (Bulan/Tahun),Frekuensi Kunjungan,
Jenis Layanan yang Sering Digunakan,Layanan Terakhir yang Digunakan,
Jenis Pakan,Merek Pakan,Tanggal Kunjungan Terakhir (2024)
```

### Dataset Processed (Dataset_Hewan_Petshop_Processed.csv)
```
Jenis Hewan,Ras,Berat Badan (Kg),Usia (Bulan),Frekuensi Kunjungan,
Layanan Sering,Layanan Terakhir,Jenis Pakan,Merek Pakan,
Hari Sejak Kunjungan,Kebutuhan Layanan Berikutnya
```

---

## TRANSFORMATIONS

### 1. No Column
**Action:** REMOVED
- Kolom nomor tidak diperlukan untuk training

### 2. Usia (Bulan/Tahun) → Usia (Bulan)
**Action:** CONVERTED
- Input: "18 bulan" → Output: 18
- Input: "5 tahun" → Output: 60 (5 × 12)
- Input: "7 bulan" → Output: 7

**Logic:**
```php
if (strpos($usiaStr, 'tahun') !== false) {
    $tahun = floatval($usiaStr);
    $usiaBulan = $tahun * 12;
} else {
    $usiaBulan = floatval($usiaStr);
}
```

### 3. Frekuensi Kunjungan
**Action:** CONVERTED to numeric
- Input: "2x/bulan" → Output: 2
- Input: "1x/3 bulan" → Output: 0.33
- Input: "3x/bulan" → Output: 3

**Logic:**
```php
if (strpos($frekuensiStr, '3 bulan') !== false) {
    $frekuensi = floatval($frekuensiStr) / 3;
} else {
    $frekuensi = floatval($frekuensiStr);
}
```

### 4. Jenis Layanan yang Sering Digunakan → Layanan Sering
**Action:** CATEGORIZED
- Input: "Grooming (bersihkan telinga)" → Output: "Grooming"
- Input: "Veterinary (cek up, sterilisasi)" → Output: "Veterinary"
- Input: "Penitipan Mingguan" → Output: "Pet Hotel"

**Categories:**
- **Grooming:** grooming, mandi, potong kuku, bersihkan, perawatan bulu, perawatan jamur
- **Veterinary:** veterinary, cek up, sterilisasi, pengobatan, vaksinasi
- **Pet Hotel:** penitipan, hotel
- **Konsultasi:** default

### 5. Layanan Terakhir yang Digunakan → Layanan Terakhir
**Action:** CATEGORIZED (same logic as above)

### 6. Tanggal Kunjungan Terakhir (2024) → Hari Sejak Kunjungan
**Action:** CONVERTED to days
- Input: "2024-01-09" → Output: 341 (days from 2024-12-15)
- Input: "2024-11-21" → Output: 24

**Logic:**
```php
$date = new DateTime($tanggal);
$today = new DateTime('2024-12-15');
$diff = $today->diff($date);
$daysSince = $diff->days;
```

### 7. Kebutuhan Layanan Berikutnya
**Action:** CREATED (Target Column)
**Logic:** Berdasarkan pola:
1. Last Veterinary + days > 90 → **Veterinary**
2. Frequent (>2x/month) + main Grooming → **Grooming**
3. Main Pet Hotel + days > 60 → **Pet Hotel**
4. Young pet (<12 months) + days > 30 → **Veterinary**
5. Last Grooming + days > 30 + frequent → **Grooming**
6. Days > 45 → **Main Category**
7. Default → **Last Category**

---

## DATA STATISTICS (502 samples)

### Target Class Distribution:
```
Grooming:     ~35%
Veterinary:   ~40%
Pet Hotel:    ~20%
Konsultasi:   ~5%
```

### Jenis Hewan:
- Kucing: ~70%
- Anjing: ~30%

### Ras Populer:
- Persia, Maine Coon, Scottish Fold, Anggora
- Golden Retriever, Bulldog, Poodle

### Berat Badan:
- Min: 2 kg
- Max: 40 kg
- Mean: ~5-6 kg (kucing), ~15-20 kg (anjing)

### Usia:
- Min: 6 bulan
- Max: 10+ tahun
- Mean: ~24-36 bulan

---

## USAGE

### Step 1: Preprocess Dataset
```bash
php ml_prediction/preprocess_dataset.php
```
**Output:** Dataset_Hewan_Petshop_Processed.csv

### Step 2: Train Model
```bash
php ml_prediction/train_model.php
```
**Output:** model_petshop.json

### Step 3: Predict
```php
$petData = [
    'jenis_hewan' => 'Kucing',
    'ras' => 'Persia',
    'berat_badan' => 4.5,
    'usia_bulan' => 18,
    'frekuensi_kunjungan' => 2,
    'layanan_sering' => 'Grooming',
    'layanan_terakhir' => 'Grooming',
    'jenis_pakan' => 'Kering',
    'merek_pakan' => 'Whiskas',
    'hari_sejak_kunjungan' => 30
];

$service = new PredictionService();
$result = $service->predictForUser($petData);
```

---

## BINNING/CATEGORIZATION

### Berat Badan:
- **Kecil:** < 5 kg
- **Sedang:** 5-10 kg
- **Besar:** 10-20 kg
- **Sangat Besar:** > 20 kg

### Usia:
- **Bayi:** < 6 bulan
- **Muda:** 6-12 bulan
- **Remaja:** 12-24 bulan
- **Dewasa:** 24-60 bulan
- **Tua:** 60-120 bulan
- **Sangat Tua:** > 120 bulan

### Frekuensi:
- **Jarang:** < 2
- **Sedang:** 2-5
- **Sering:** > 5

---

## FILES UPDATED

✓ preprocess_dataset.php - NEW (preprocessing script)
✓ train_model.php - Updated (use processed CSV)
✓ test_quick.php - Updated (use processed CSV)
✓ PredictionService.php - Updated (add Veterinary category)

---

## NEXT STEPS

1. Run preprocessing:
   ```bash
   php ml_prediction/preprocess_dataset.php
   ```

2. Verify output:
   - Check Dataset_Hewan_Petshop_Processed.csv
   - Verify target distribution

3. Train model:
   ```bash
   php ml_prediction/train_model.php
   ```

4. Test accuracy:
   - Expected: 85-95% (502 samples)
   - If < 80%, check data quality

5. Deploy to production interfaces

---

**Note:** Dataset Makassar 2024 memiliki 502 samples yang sangat baik untuk training!
Akurasi diharapkan lebih tinggi dari dataset sebelumnya (50 samples).
