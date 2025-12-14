# 🐾 Xboxpetshop - Machine Learning Decision Tree System

## 📋 Deskripsi

Sistem prediksi kebutuhan layanan untuk hewan peliharaan menggunakan algoritma **Decision Tree (C4.5)** yang diimplementasikan dengan **PHP Native** tanpa library eksternal.

## 🎯 Fitur Utama

- ✅ **Algoritma Decision Tree C4.5** - Pure PHP implementation
- ✅ **Data Preprocessing** - Handling CSV, binning, normalisasi
- ✅ **Training & Model Persistence** - Save/load model dalam format JSON
- ✅ **Role-Based Prediction** - Output berbeda untuk User, Kasir, Admin, Dokter
- ✅ **Feature Importance Analysis** - Mengetahui fitur paling berpengaruh
- ✅ **Accuracy Calculation** - Evaluasi performa model

## 📁 Struktur File

```
ml_prediction/
├── DecisionTree.php           # Class algoritma Decision Tree
├── DataPreprocessor.php       # Class preprocessing data
├── PredictionService.php      # Service untuk prediksi berbasis role
├── train_model.php            # Script training model
├── data_training.csv          # Dataset training (50 sampel)
├── model_petshop.json         # Model hasil training (auto-generated)
└── model_petshop_metadata.json # Metadata model (auto-generated)

user/
└── prediksi_layanan.php       # Interface untuk User/Pelanggan

kasir/
└── prediksi_penjualan.php     # Interface untuk Kasir (Upselling)

admin/
└── ml_management.php          # Dashboard Admin (Model Management)

dokter/
└── ai_assistant.php           # Interface untuk Dokter (Medical Validation)
```

## 🚀 Cara Penggunaan

### 1️⃣ Training Model (Pertama Kali)

**Via Browser:**
```
http://localhost/rina212238/ml_prediction/train_model.php?action=train
```

**Via Command Line:**
```bash
php ml_prediction/train_model.php
```

**Output:**
- `model_petshop.json` - Model yang sudah di-training
- `model_petshop_metadata.json` - Informasi akurasi dan feature importance

### 2️⃣ Menggunakan Prediksi

#### A. Untuk User (Pelanggan)

```php
require_once 'ml_prediction/PredictionService.php';

$petData = [
    'jenis_hewan' => 'Kucing',
    'ras' => 'Persia',
    'berat_badan' => 4.5,
    'usia_bulan' => 18,
    'frekuensi_kunjungan' => 5,
    'layanan_sering' => 'Grooming',
    'layanan_terakhir' => 'Grooming',
    'jenis_pakan' => 'Whiskas Premium',
    'merek_pakan' => 'Whiskas',
    'hari_sejak_kunjungan' => 30
];

$predictionService = new PredictionService();
$result = $predictionService->predictForUser($petData);

echo $result['title'];        // "🐾 Waktunya Grooming!"
echo $result['message'];      // Pesan user-friendly
echo $result['prediction'];   // "Grooming"
echo $result['confidence'];   // 85.5
```

**Akses Interface:**
```
http://localhost/rina212238/user/prediksi_layanan.php
```

#### B. Untuk Kasir (Upselling)

```php
$result = $predictionService->predictForKasir($petData);

echo $result['main_service'];          // "Grooming"
echo $result['price_estimate'];        // "Rp 75.000 - 150.000"
echo $result['upsell_opportunities'];  // Array item upselling
echo $result['promo_suggestion'];      // Saran promo
echo $result['sales_script'];          // Script penjualan
```

**Akses Interface:**
```
http://localhost/rina212238/kasir/prediksi_penjualan.php?pet_id=123
```

#### C. Untuk Admin (Management)

```php
$result = $predictionService->predictForAdmin();

echo $result['model_info']['accuracy'];     // 92.5
echo $result['model_info']['training_date']; // "2024-12-15 10:30:00"
print_r($result['feature_importance']);      // Array feature importance
```

**Akses Interface:**
```
http://localhost/rina212238/admin/ml_management.php
```

#### D. Untuk Dokter (Medical Validation)

```php
$result = $predictionService->predictForDokter($petData);

echo $result['prediction'];                        // "Vaksinasi"
echo $result['medical_validation']['health_note']; // Catatan medis
print_r($result['medical_validation']['check_points']); // Poin pemeriksaan
echo $result['medical_validation']['concern_level'];    // "High/Medium/Low"
```

**Akses Interface:**
```
http://localhost/rina212238/dokter/ai_assistant.php?pet_id=123
```

## 📊 Dataset Format

### Input Features (10 fitur):

1. **Jenis Hewan** - Kategorikal (Kucing, Anjing, dll)
2. **Ras** - Kategorikal (Persia, Golden Retriever, dll)
3. **Berat Badan (Kg)** - Numerik → Dikategorikan: Kecil, Sedang, Besar, Sangat Besar
4. **Usia (Bulan)** - Numerik → Dikategorikan: Bayi, Muda, Remaja, Dewasa, Tua, Sangat Tua
5. **Frekuensi Kunjungan** - Numerik → Dikategorikan: Jarang, Sedang, Sering
6. **Layanan Sering** - Kategorikal (Grooming, Vaksinasi, dll)
7. **Layanan Terakhir** - Kategorikal
8. **Jenis Pakan** - Kategorikal
9. **Merek Pakan** - Kategorikal
10. **Hari Sejak Kunjungan** - Numerik

### Target Output:

**Kebutuhan Layanan Berikutnya** (5 kategori):
- Grooming
- Vaksinasi
- Pengobatan
- Pet Hotel
- Konsultasi

## 🔧 Preprocessing Otomatis

### Binning Berat Badan:
```php
$preprocessor->categorizeWeight('Berat Badan (Kg)', 'general');
// Hasil: Kecil, Sedang, Besar, Sangat Besar
```

### Binning Usia:
```php
$preprocessor->categorizeAge('Usia (Bulan)', 'bulan');
// Hasil: Bayi, Muda, Remaja, Dewasa, Tua, Sangat Tua
```

### Binning Frekuensi:
```php
$preprocessor->autoDiscretize('Frekuensi Kunjungan', 3, ['Jarang', 'Sedang', 'Sering']);
```

## 📈 Evaluasi Model

### Akurasi:
```php
$model = new DecisionTree();
$model->loadModel('model_petshop.json');

$accuracy = $model->calculateAccuracy($testData, $testLabels);
echo "Akurasi: " . $accuracy . "%";
```

### Feature Importance:
```php
$importance = $model->getFeatureImportance($featureNames);
/*
Output example:
[
    'Layanan Terakhir' => 35.2,
    'Frekuensi Kunjungan' => 28.5,
    'Usia' => 15.3,
    ...
]
*/
```

### Confidence Score:
```php
$result = $model->predictWithConfidence($sample);
echo $result['prediction'];   // "Grooming"
echo $result['confidence'];   // 87.5
print_r($result['distribution']); 
/*
[
    'Grooming' => 87.5,
    'Vaksinasi' => 8.3,
    'Pet Hotel' => 4.2
]
*/
```

## 🔄 Retrain Model dengan Data Baru

### 1. Update Dataset CSV

Tambahkan data baru ke `data_training.csv` dengan format:
```csv
Jenis Hewan,Ras,Berat Badan (Kg),Usia (Bulan),...,Kebutuhan Layanan Berikutnya
Kucing,Persia,4.5,18,...,Grooming
```

### 2. Jalankan Training Ulang

```bash
php ml_prediction/train_model.php
```

Atau via admin dashboard:
```
Admin → ML Management → Retrain Model
```

## 🎓 Penjelasan Algoritma

### Decision Tree C4.5

#### 1. Entropy (Ukuran Ketidakpastian)

$$Entropy(S) = -\sum_{i=1}^{c} p_i \log_2(p_i)$$

Dimana:
- $S$ = Dataset
- $c$ = Jumlah kelas
- $p_i$ = Proporsi sampel kelas ke-i

**Implementasi PHP:**
```php
private function calculateEntropy($labels) {
    $classCounts = array_count_values($labels);
    $entropy = 0.0;
    $total = count($labels);
    
    foreach ($classCounts as $count) {
        $probability = $count / $total;
        if ($probability > 0) {
            $entropy -= $probability * log($probability, 2);
        }
    }
    
    return $entropy;
}
```

#### 2. Information Gain (Keuntungan Informasi)

$$Gain(S, A) = Entropy(S) - \sum_{v \in Values(A)} \frac{|S_v|}{|S|} Entropy(S_v)$$

Dimana:
- $A$ = Atribut/Fitur
- $Values(A)$ = Nilai-nilai pada atribut A
- $S_v$ = Subset data dengan nilai v pada atribut A

**Implementasi PHP:**
```php
private function calculateInformationGain($data, $labels, $featureIdx, $uniqueValues) {
    $totalSamples = count($labels);
    $parentEntropy = $this->calculateEntropy($labels);
    
    $weightedChildEntropy = 0.0;
    
    foreach ($uniqueValues as $value) {
        $subLabels = []; // Get labels for this value
        
        foreach ($data as $idx => $sample) {
            if ($sample[$featureIdx] == $value) {
                $subLabels[] = $labels[$idx];
            }
        }
        
        $weight = count($subLabels) / $totalSamples;
        $weightedChildEntropy += $weight * $this->calculateEntropy($subLabels);
    }
    
    return $parentEntropy - $weightedChildEntropy;
}
```

#### 3. Proses Pembangunan Tree

1. **Hitung Entropy** dataset awal
2. **Loop setiap fitur:**
   - Hitung Information Gain
   - Pilih fitur dengan Gain tertinggi sebagai **Root Node**
3. **Split data** berdasarkan nilai fitur terpilih
4. **Rekursif** untuk setiap subset hingga:
   - Semua data sudah pure (1 kelas)
   - Kedalaman maksimum tercapai
   - Jumlah sampel < minimum

**Stopping Conditions:**
```php
if ($depth >= $this->maxDepth || 
    $numSamples < $this->minSamplesLeaf || 
    $this->isPure($labels) ||
    $numFeatures == 0) {
    return $this->createLeafNode($labels);
}
```

## 🛠️ Troubleshooting

### Error: "Model file not found"
**Solusi:** Jalankan training terlebih dahulu
```bash
php ml_prediction/train_model.php
```

### Error: "CSV file not found"
**Solusi:** Pastikan file `data_training.csv` ada di folder `ml_prediction/`

### Akurasi rendah (< 70%)
**Solusi:**
1. Tambah data training (minimal 100+ sampel)
2. Periksa kualitas data (missing values, inconsistency)
3. Atur parameter `maxDepth` dan `minSamplesLeaf`

### Overfitting (Akurasi training tinggi, tapi prediksi buruk)
**Solusi:**
```php
$model = new DecisionTree(
    maxDepth: 5,           // Kurangi depth (default: 10)
    minSamplesLeaf: 5      // Tambah min samples (default: 1)
);
```

## 📝 Requirements

- PHP 7.4 atau lebih tinggi
- Extension: `json`, `fileinfo` (biasanya sudah aktif)
- Web Server: Apache/Nginx
- Database: MySQL (untuk integrasi dengan sistem utama)

## 🔐 Security Notes

1. **Validasi Input:**
```php
$petData['berat_badan'] = floatval($input['berat_badan']);
$petData['jenis_hewan'] = htmlspecialchars($input['jenis_hewan']);
```

2. **File Upload CSV:** Validasi format dan size
```php
if ($_FILES['dataset']['type'] !== 'text/csv') {
    die('Only CSV files allowed');
}
```

3. **Model File Access:** Simpan di luar public folder atau proteksi dengan .htaccess

## 📚 Contoh Penggunaan Lengkap

### Scenario: Prediksi untuk Customer Baru

```php
<?php
// 1. Ambil data dari form/database
$petData = [
    'jenis_hewan' => $_POST['jenis_hewan'],
    'ras' => $_POST['ras'],
    'berat_badan' => floatval($_POST['berat_badan']),
    'usia_bulan' => intval($_POST['usia_bulan']),
    // ... data lainnya
];

// 2. Load service
require_once 'ml_prediction/PredictionService.php';
$predictionService = new PredictionService();

// 3. Prediksi berdasarkan role
switch ($_SESSION['role']) {
    case 'user':
        $result = $predictionService->predictForUser($petData);
        // Tampilkan rekomendasi ramah user
        break;
        
    case 'kasir':
        $result = $predictionService->predictForKasir($petData);
        // Tampilkan upselling & promo
        break;
        
    case 'admin':
        $result = $predictionService->predictForAdmin();
        // Tampilkan analytics
        break;
        
    case 'dokter':
        $result = $predictionService->predictForDokter($petData);
        // Tampilkan medical validation
        break;
}

// 4. Render output
?>
<div class="prediction-result">
    <h3><?= $result['prediction'] ?></h3>
    <p>Confidence: <?= $result['confidence'] ?>%</p>
</div>
```

## 🌟 Fitur Lanjutan (Future Enhancement)

- [ ] Multi-model comparison (Random Forest, SVM)
- [ ] Real-time accuracy monitoring
- [ ] A/B testing untuk model baru
- [ ] Export prediksi ke Excel/PDF
- [ ] API endpoint untuk mobile app
- [ ] Visualisasi decision tree (HTML/SVG)

## 👥 Tim Pengembang

**Xboxpetshop Development Team**
- Machine Learning Engineer
- Full Stack Developer
- Database Administrator

## 📞 Support

Jika ada pertanyaan atau bug, silakan hubungi:
- Email: support@xboxpetshop.com
- GitHub Issues: [Create Issue]

---

**© 2024 Xboxpetshop - AI-Powered Pet Care System**
