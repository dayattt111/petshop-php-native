# DOKUMENTASI JURNAL
## SISTEM PREDIKSI KEBUTUHAN LAYANAN PETSHOP MENGGUNAKAN METODE DECISION TREE (C4.5)

> File ini disusun agar bisa langsung dipakai sebagai konteks/prompt ke GPT/Gemini untuk membantu penulisan jurnal ilmiah berdasarkan proyek dan data Anda.

---

## 1. Latar Belakang

Xboxpetshop adalah sistem informasi petshop yang sudah memiliki modul penjualan pakan, aksesoris, pemeriksaan hewan, dan manajemen user (admin, dokter, kasir, pelanggan). Untuk meningkatkan pelayanan, dikembangkan modul **Machine Learning** untuk **memprediksi kebutuhan layanan berikutnya** bagi hewan peliharaan, menggunakan algoritma **Decision Tree (C4.5)** yang diimplementasikan dengan **PHP Native** (tanpa library eksternal).

Tujuan penelitian/sistem:
- Memprediksi layanan berikutnya yang paling dibutuhkan oleh hewan (misalnya Grooming, Veterinary/Pengobatan, Pet Hotel).
- Membantu **user/pelanggan** mendapat rekomendasi layanan.
- Membantu **kasir** melakukan upselling/promo.
- Membantu **dokter** melakukan validasi medis berbasis riwayat kunjungan.
- Memberi **admin** insight tentang performa model dan fitur paling berpengaruh.

---

## 2. Deskripsi Dataset

### 2.1 Sumber dan Ukuran Data

- Sumber: Dataset internal petshop "Dataset_Hewan_Petshop_Makassar_2024.csv" (data Anda sendiri).
- Jumlah baris awal: **502** catatan hewan/kunjungan.
- Setelah pembersihan (missing/invalid row): **±500 sampel** dipakai untuk training.

### 2.2 Atribut pada Dataset Asli

Contoh struktur kolom pada dataset asli (sebelum preprocessing):

- `No` – ID/increment.
- `Jenis Hewan` – Kucing, Anjing, dll.
- `Ras` – Misalnya Persia, Anggora, Golden Retriever, dll.
- `Berat Badan (Kg)` – Berat aktual dalam kilogram.
- `Usia (Bulan/Tahun)` – Campuran format, contoh: "18 bulan", "5 tahun".
- `Frekuensi Kunjungan` – Contoh: "2x/bulan", "1x/3 bulan".
- `Layanan Sering` – Layanan yang paling sering digunakan.
- `Layanan Terakhir` – Layanan terakhir yang digunakan.
- `Jenis Pakan` – Kering/Basah/Campuran.
- `Merek Pakan` – Merek produk pakan yang digunakan.
- `Tanggal Kunjungan Terakhir` – Tanggal riil kunjungan.
- (Tidak ada kolom eksplisit "Kebutuhan Layanan Berikutnya").

### 2.3 Atribut Setelah Preprocessing

File hasil preprocessing: `Dataset_Hewan_Petshop_Processed.csv`.

Fitur (X) yang digunakan untuk training (10 fitur utama):

1. **Jenis Hewan** (kategori)  
2. **Ras** (kategori)  
3. **Berat Badan (Kategori)** – hasil binning dari berat (Kg):  
   - Kecil, Sedang, Besar, Sangat Besar.  
4. **Usia (Kategori)** – hasil konversi usia ke bulan, lalu dibinning:  
   - Bayi (<6 bulan), Muda (6–12 bulan), Remaja (1–2 tahun), Dewasa (2–5 tahun), Tua (5–10 tahun), Sangat Tua (>10 tahun).  
5. **Frekuensi Kunjungan (Kategori)** – dari nilai numerik/bulan:  
   - Jarang, Sedang, Sering.  
6. **Layanan Sering** (kategori) – misalnya Grooming, Veterinary, Pet Hotel.  
7. **Layanan Terakhir** (kategori).  
8. **Jenis Pakan** (kategori) – Kering/Basah/Campuran.  
9. **Merek Pakan** (kategori).  
10. **Hari Sejak Kunjungan Terakhir** – nilai numerik hasil selisih hari dari tanggal referensi.

Target (y) / kelas keluaran:
- **Grooming**  
- **Veterinary/Pengobatan** (disatukan sebagai kategori "Veterinary" pada model)  
- **Pet Hotel**

---

## 3. Proses Preprocessing Data

Preprocessing diimplementasikan di file [ml_prediction/DataPreprocessor.php](ml_prediction/DataPreprocessor.php) dan script [ml_prediction/preprocess_dataset.php](ml_prediction/preprocess_dataset.php).

### 3.1 Pembersihan dan Transformasi Utama

1. **Menghapus kolom No** yang tidak informatif.
2. **Konversi Usia (Bulan/Tahun) → usia dalam bulan**:  
   - Jika mengandung kata "tahun": dikali 12.  
   - Jika mengandung kata "bulan": langsung diambil angkanya.  
3. **Konversi Frekuensi Kunjungan**:  
   - Contoh: "2x/bulan" → 2.  
   - "1x/3 bulan" → 1/3 (kemudian dapat diproyeksikan ke frekuensi per bulan).  
4. **Konversi Tanggal Kunjungan → Hari Sejak Kunjungan**:  
   - $\text{hari\_sejak\_kunjungan} = \text{tanggal\_referensi} - \text{tanggal\_terakhir}$.  
5. **Normalisasi teks**: trimming, huruf awal kapital, konsistensi penulisan.
6. **Penanganan missing value**:  
   - Baris dengan data sangat tidak lengkap dibuang, sisanya diisi dengan modus/nilai default bila perlu.

### 3.2 Pembentukan Target (Kebutuhan Layanan Berikutnya)

Karena dataset asli tidak memiliki kolom target eksplisit, target dibentuk dengan **aturan logika berbasis pola** (rule-based labelling), misalnya:

- Jika `Layanan Terakhir = Grooming` **dan** `Hari Sejak Kunjungan` besar, maka target condong ke **Grooming** lagi.
- Jika terdapat indikasi layanan medis terakhir (vaksinasi/pengobatan) dan frekuensi tinggi, target condong ke **Veterinary**.
- Jika pola kunjungan dekat dengan periode libur panjang dan sering memakai jasa penitipan, target condong ke **Pet Hotel**.

Setelah labelling, dataset hasil dipakai sebagai ground truth untuk training Decision Tree.

---

## 4. Metode Decision Tree (C4.5)

Algoritma inti diimplementasikan di [ml_prediction/DecisionTree.php](ml_prediction/DecisionTree.php) dengan bahasa PHP Native.

### 4.1 Konsep Dasar

- Setiap node memecah data berdasarkan fitur yang **memaksimalkan Information Gain** (C4.5 menggunakan **Entropy** sebagai ukuran impurity).  
- Pohon dibangun secara rekursif sampai memenuhi kondisi berhenti (misalnya kedalaman maksimum, atau semua sampel di node sama kelasnya).

### 4.2 Rumus Entropy

Untuk suatu himpunan data $S$ dengan kelas $c_1, c_2, \dots, c_k$ dan proporsi $p_i$ untuk kelas $c_i$:

$$
Entropy(S) = - \sum_{i=1}^{k} p_i \log_2(p_i)
$$

### 4.3 Rumus Gain (Information Gain)

Jika fitur $A$ memiliki nilai-nilai $v$ dan membagi data menjadi subset $S_v$:

$$
Gain(S, A) = Entropy(S) - \sum_{v \in Values(A)} \frac{|S_v|}{|S|} Entropy(S_v)
$$

Fitur dengan nilai Gain tertinggi dipilih sebagai pemecah (split) di node tersebut.

---

## 5. Contoh Tabel Perhitungan Entropy & Information Gain

Untuk keperluan jurnal, berikut **contoh ilustrasi** perhitungan menggunakan subset kecil (bukan seluruh 500 data, tetapi tetap konsisten dengan skenario petshop).

### 5.1 Contoh Subset Data (5 Sampel)

Misal diambil 5 baris data hewan kucing dari dataset Anda (ilustrasi):

| ID | Jenis Hewan | Ras        | Frek. Kunjungan | Layanan Sering | Hari Sejak Kunjungan | Target (Layanan Berikutnya) |
|----|-------------|------------|------------------|----------------|----------------------|-----------------------------|
| 1  | Kucing      | Persia     | 3x/bulan        | Grooming       | 20                   | Grooming                    |
| 2  | Kucing      | Persia     | 2x/bulan        | Grooming       | 40                   | Grooming                    |
| 3  | Kucing      | Anggora    | 1x/bulan        | Veterinary     | 10                   | Veterinary                  |
| 4  | Kucing      | Anggora    | 1x/bulan        | Veterinary     | 15                   | Veterinary                  |
| 5  | Kucing      | Persia     | 1x/bulan        | Grooming       | 90                   | Pet Hotel                   |

Distribusi kelas pada subset $S$:
- Grooming: 2 sampel  
- Veterinary: 2 sampel  
- Pet Hotel: 1 sampel  

Sehingga probabilitas:
- $p(\text{Grooming}) = 2/5$  
- $p(\text{Veterinary}) = 2/5$  
- $p(\text{Pet Hotel}) = 1/5$

**Entropy awal $Entropy(S)$:**

$$
\begin{aligned}
Entropy(S) &= -\left(\frac{2}{5}\log_2\frac{2}{5} + \frac{2}{5}\log_2\frac{2}{5} + \frac{1}{5}\log_2\frac{1}{5}\right) \\
&\approx -\big(0.4 \cdot (-1.3219) + 0.4 \cdot (-1.3219) + 0.2 \cdot (-2.3219)\big) \\
&\approx 1.522 \text{ bit}
\end{aligned}
$$

### 5.2 Contoh Perhitungan Gain untuk Fitur "Ras"

Kita lihat pemecahan berdasarkan fitur **Ras**:

- Untuk **Ras = Persia** (ID 1,2,5): Target = \{Grooming, Grooming, Pet Hotel\}  
  - Grooming: 2, Pet Hotel: 1, Veterinary: 0.  

  $$
  Entropy(S_{Persia}) = -\left(\frac{2}{3}\log_2\frac{2}{3} + \frac{1}{3}\log_2\frac{1}{3}\right)
  \approx 0.9183
  $$

- Untuk **Ras = Anggora** (ID 3,4): Target = \{Veterinary, Veterinary\}
  - Semua kelas Veterinary → Entropy = 0.

Bobot masing-masing subset:
- $|S_{Persia}| = 3$, $|S_{Anggora}| = 2$, $|S| = 5$.

**Entropy setelah split oleh Ras:**

$$
\begin{aligned}
Entropy_{split}(S, Ras) &= \frac{3}{5}Entropy(S_{Persia}) + \frac{2}{5}Entropy(S_{Anggora}) \\
&= \frac{3}{5}(0.9183) + \frac{2}{5}(0) \\
&\approx 0.55098
\end{aligned}
$$

**Information Gain untuk fitur Ras:**

$$
\begin{aligned}
Gain(S, Ras) &= Entropy(S) - Entropy_{split}(S, Ras) \\
&\approx 1.522 - 0.551 \\
&\approx 0.971
\end{aligned}
$$

Jika dibandingkan dengan fitur lain (misalnya Frekuensi Kunjungan atau Hari Sejak Kunjungan), fitur dengan Gain terbesar akan dipilih sebagai akar atau node berikutnya pada Decision Tree.

> Catatan: Di implementasi nyata, perhitungan dilakukan otomatis untuk seluruh 500 sampel, bukan hanya 5 sampel contoh ini.

---

## 6. Implementasi Algoritma dalam PHP

### 6.1 Struktur Kode Inti

- [ml_prediction/DecisionTree.php](ml_prediction/DecisionTree.php)  
  Mengandung fungsi:
  - `fit($data, $labels, $featureNames)` – membangun pohon.
  - `predict($sample)` – prediksi tunggal.
  - `predictWithConfidence($sample)` – prediksi + distribusi probabilitas kelas.
  - `calculateEntropy(...)`, `calculateInformationGain(...)` – perhitungan entropy & gain.
  - `saveModel(...)` dan `loadModel(...)` – simpan/muat model JSON.

- [ml_prediction/DataPreprocessor.php](ml_prediction/DataPreprocessor.php)  
  Mengelola:
  - Load CSV, pembersihan, binning, normalisasi teks.

- [ml_prediction/PredictionService.php](ml_prediction/PredictionService.php)  
  Layer abstraksi untuk 4 role:
  - `predictForUser($petData)`  
  - `predictForKasir($petData)`  
  - `predictForAdmin()`  
  - `predictForDokter($petData)`

### 6.2 Proses Training

Script training: [ml_prediction/train_model.php](ml_prediction/train_model.php)

Ringkasan langkah:
1. Load `Dataset_Hewan_Petshop_Processed.csv`.
2. Lakukan preprocessing/binner untuk fitur numerik.
3. Bagi menjadi fitur (X) dan target (y).
4. Panggil `DecisionTree->fit(X, y, featureNames)`.
5. Hitung akurasi training dengan `calculateAccuracy`.
6. Hitung feature importance.
7. Simpan ke `model_petshop.json` + `model_petshop_metadata.json`.

### 6.3 Hasil Pelatihan (Dari Metadata)

Berikut ringkasan hasil pelatihan terakhir (sesuai log dan metadata):

| Parameter                  | Nilai                     |
|---------------------------|---------------------------|
| Jumlah sampel training    | ~500 sampel              |
| Jumlah fitur input        | 10 fitur                 |
| Jumlah kelas target       | 3 (Grooming, Veterinary, Pet Hotel) |
| Akurasi training          | 100% (pada data training) |
| File model                | `model_petshop.json`      |
| File metadata             | `model_petshop_metadata.json` |

### 6.4 Contoh Feature Importance (Dari Metadata)

Contoh hasil feature importance (persentase kontribusi, dari metadata yang dihasilkan):

| Fitur                    | Importance (%) |
|--------------------------|----------------|
| Hari Sejak Kunjungan     | ±60.68         |
| Ras                      | ±20.15         |
| Jenis Hewan              | ±9.10          |
| Frekuensi Kunjungan      | (lebih kecil)  |
| Layanan Sering           | (lebih kecil)  |
| Lainnya                  | (sisa persen)  |

> Angka di atas menggambarkan bahwa **riwayat waktu kunjungan terakhir** dan **ras hewan** sangat berpengaruh dalam menentukan kebutuhan layanan berikutnya.

---

## 7. Integrasi dengan Sistem Petshop (Role-Based)

Integrasi UI sudah dilakukan pada beberapa file:

- Admin: [admin/ml_management.php](admin/ml_management.php) dan [admin/ml_preview.php](admin/ml_preview.php)  
   Dashboard untuk melihat akurasi, jumlah sampel, fitur, kelas target, feature importance, dan **preview tampilan semua role** dalam satu halaman.

- User: [user/prediksi_layanan.php](user/prediksi_layanan.php)  
   Menampilkan kartu rekomendasi layanan dengan pesan ramah, confidence score, dan alternative services.

- Kasir: [kasir/prediksi_penjualan.php](kasir/prediksi_penjualan.php)  
   Menampilkan rekomendasi layanan + skrip penjualan dan item upselling.

- Dokter: [dokter/ai_assistant.php](dokter/ai_assistant.php)  
   Menampilkan rekomendasi AI, catatan medis, check points, dan form validasi dokter.

Menu integrasi dirangkum di [ml_prediction/MENU_INTEGRATION.md](ml_prediction/MENU_INTEGRATION.md).

### 7.1 Tampilan Antarmuka Admin

Pada role **admin**, terdapat dua halaman utama terkait modul ML:

1. **Halaman ML Management** – [admin/ml_management.php](admin/ml_management.php)  
    - Layout menggunakan sidebar kiri (Admin Panel) dan konten kanan.  
    - Bagian atas menampilkan **4 kartu ringkasan**:  
       - Model Accuracy (%).  
       - Training Samples.  
       - Features.  
       - Target Classes.  
    - Di bawahnya terdapat **kartu Model Information** yang menampilkan nama file model, tanggal training, algoritma, dan daftar kelas target.  
    - Berikutnya ada **bagian Feature Importance** berupa daftar bar dengan progress bar persentase kontribusi tiap fitur (misalnya Hari Sejak Kunjungan, Ras, Jenis Hewan).  
    - Admin juga dapat menekan tombol untuk melakukan retrain model melalui script `train_model.php`.

2. **Halaman ML System Preview** – [admin/ml_preview.php](admin/ml_preview.php)  
    - Menyediakan **tab interface** untuk melihat tampilan keluaran AI dari perspektif keempat role tanpa harus login sebagai role tersebut:  
       - Tab **Admin Dashboard**: ringkasan akurasi, training samples, fitur, target classes, serta top 5 feature importance dengan progress bar.  
       - Tab **Kasir View**: menampilkan contoh customer & pet info, prediksi layanan utama, confidence %, skrip penjualan, dan daftar upselling items beserta harga.  
       - Tab **Dokter View**: menampilkan informasi pasien (jenis hewan, ras, usia, berat, hari sejak kunjungan), rekomendasi AI, confidence %, concern level, dan daftar check points medis yang perlu divalidasi.  
       - Tab **User View**: menampilkan kartu rekomendasi layanan (judul, ikon emoji, pesan, confidence badge), tombol aksi (action button), serta daftar alternative services dengan progress bar probabilitas.  
    - Di bagian bawah terdapat **kartu ringkasan** yang menjelaskan fokus informasi tiap role (Admin, Kasir, Dokter, User) sehingga memudahkan penjelasan di jurnal tentang perbedaan sudut pandang UI per role.

Tampilan ini bisa dijadikan bahan di jurnal pada subbab **Implementasi Sistem** (desain UI/UX admin) maupun **Hasil dan Pembahasan** (bagaimana admin memonitor dan mengevaluasi performa model). 

---

## 8. Tabel Ringkasan Hasil Sistem

Berikut tabel ringkasan hasil yang bisa digunakan di bab **Hasil dan Pembahasan** jurnal:

### 8.1 Ringkasan Kinerja Model

| No | Aspek              | Nilai/Deskripsi                                      |
|----|--------------------|------------------------------------------------------|
| 1  | Jumlah Sampel      | ±500 sampel dari Dataset_Hewan_Petshop_Makassar_2024 |
| 2  | Jumlah Fitur       | 10 fitur utama                                       |
| 3  | Jumlah Kelas       | 3 kelas (Grooming, Veterinary, Pet Hotel)           |
| 4  | Algoritma          | Decision Tree (C4.5)                                 |
| 5  | Akurasi Training   | 100% (pada data training)                            |
| 6  | Fitur Terpenting   | Hari Sejak Kunjungan, Ras, Jenis Hewan              |

### 8.2 Contoh Output per Role

| Role   | Fokus Informasi                            | Contoh Output Utama                                      |
|--------|--------------------------------------------|----------------------------------------------------------|
| User   | Rekomendasi layanan                        | "🐾 Waktunya Grooming!" + confidence + alternative      |
| Kasir  | Upselling & promo                          | Skrip penjualan, list item upselling, harga, promo      |
| Dokter | Validasi medis & riwayat kesehatan         | Prediksi layanan, concern level, health check points    |
| Admin  | Monitoring model & analitik                | Akurasi, feature importance, informasi model, rekomendasi |

---

## 9. Ide Struktur Bab Jurnal

Berdasarkan dokumentasi ini, Anda bisa menyusun bab jurnal seperti berikut:

1. **Pendahuluan**  
   - Latar belakang pentingnya personalisasi layanan petshop.  
   - Permasalahan: belum adanya sistem prediksi kebutuhan layanan.  
   - Tujuan: membangun sistem prediksi berbasis Decision Tree C4.5.

2. **Tinjauan Pustaka**  
   - Konsep Decision Tree & C4.5.  
   - Penelitian terkait di domain petshop/veteriner/retail.

3. **Metodologi Penelitian**  
   - Deskripsi dataset Makassar 2024.  
   - Proses preprocessing (konversi usia, frekuensi, tanggal, labelling target).  
   - Deskripsi algoritma (rumus entropy & gain, flowchart).  
   - Desain arsitektur sistem (diagram: database → preprocessing → training → prediction service → UI role-based).

4. **Implementasi Sistem**  
   - Bahasa & environment (PHP Native, XAMPP/Laragon, MariaDB).  
   - Struktur folder & class utama.  
   - Penjelasan singkat tiap interface (user, kasir, dokter, admin).

5. **Hasil dan Pembahasan**  
   - Tabel ringkasan kinerja model.  
   - Feature importance dan interpretasi.  
   - Contoh kasus (studi kasus 1–2 hewan dengan riwayat lengkap).  
   - Analisis kelebihan/keterbatasan (misalnya overfitting karena akurasi 100% pada training).

6. **Kesimpulan dan Saran**  
   - Kesimpulan utama dari implementasi dan evaluasi.  
   - Saran pengembangan: tambah dataset, validasi cross-validation, bandingkan dengan algoritma lain (Random Forest, SVM, dsb.).

---

## 10. Contoh Prompt untuk GPT/Gemini (Lanjutan)

Jika nanti Anda ingin menggunakan file ini sebagai konteks, Anda bisa memberikan prompt seperti:

> "Berikut adalah dokumentasi lengkap proyek saya tentang sistem prediksi kebutuhan layanan petshop menggunakan Decision Tree C4.5 (lihat teks di atas). Tolong bantu saya menyusun draft bab Metodologi untuk jurnal ilmiah (bahasa Indonesia), dengan struktur: 1) Dataset, 2) Preprocessing, 3) Algoritma Decision Tree C4.5, 4) Arsitektur Sistem. Sertakan rumus dan penjelasan langkah-langkahnya secara runtut."

Atau:

> "Dengan mengacu pada dokumentasi proyek Decision Tree Petshop di atas, tolong buatkan bagian Hasil dan Pembahasan yang menjelaskan akurasi model, feature importance, serta contoh kasus prediksi untuk satu hewan peliharaan."

---

**Catatan:** Jika di kemudian hari dataset atau hasil training berubah (misalnya akurasi baru, jumlah sampel, atau jumlah kelas berbeda), Anda bisa mengedit bagian tabel di bab 6 dan 8 agar tetap konsisten dengan kondisi terbaru.
