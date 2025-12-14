<?php
/**
 * Prediction Service - Role-Based Output
 * Provides predictions for different user roles
 * 
 * @author Xboxpetshop Development Team
 */

require_once __DIR__ . '/DecisionTree.php';
require_once __DIR__ . '/../config/koneksi.php';

class PredictionService {
    
    private $model;
    private $modelFile;
    private $metadata;
    private $db;
    
    public function __construct($modelFile = null, $dbConnection = null) {
        $this->modelFile = $modelFile ?? __DIR__ . '/model_petshop.json';
        $this->db = $dbConnection;
        $this->loadModel();
    }
    
    /**
     * Load trained model
     */
    private function loadModel() {
        if (!file_exists($this->modelFile)) {
            throw new Exception("Model file not found. Please train the model first.");
        }
        
        $this->model = new DecisionTree();
        $this->model->loadModel($this->modelFile);
        
        // Load metadata
        $metadataFile = str_replace('.json', '_metadata.json', $this->modelFile);
        if (file_exists($metadataFile)) {
            $this->metadata = json_decode(file_get_contents($metadataFile), true);
        }
    }
    
    /**
     * Get pet data from database
     * 
     * @param int $petId Pet ID or customer ID
     * @param string $type Type: 'pet' or 'customer'
     * @return array Pet data
     */
    public function getPetDataFromDB($petId, $type = 'pet') {
        if (!$this->db) {
            throw new Exception("Database connection not available");
        }
        
        // This is a sample query - adjust based on your actual database structure
        if ($type === 'pet') {
            $query = "
                SELECT 
                    p.jenis_hewan,
                    p.ras,
                    p.berat_badan,
                    p.usia_bulan,
                    COUNT(pk.id_periksa) as frekuensi_kunjungan,
                    (SELECT jenis_layanan FROM periksa WHERE id_pet = p.id_pet ORDER BY tanggal DESC LIMIT 1) as layanan_terakhir,
                    (SELECT jenis_layanan FROM periksa WHERE id_pet = p.id_pet GROUP BY jenis_layanan ORDER BY COUNT(*) DESC LIMIT 1) as layanan_sering,
                    ps.jenis_pakan,
                    ps.merek_pakan,
                    DATEDIFF(NOW(), (SELECT tanggal FROM periksa WHERE id_pet = p.id_pet ORDER BY tanggal DESC LIMIT 1)) as hari_sejak_kunjungan
                FROM pets p
                LEFT JOIN periksa pk ON p.id_pet = pk.id_pet
                LEFT JOIN pesanan ps ON p.id_pet = ps.id_pet
                WHERE p.id_pet = ?
                GROUP BY p.id_pet
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $petId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Prepare features from raw data
     * 
     * @param array $rawData Raw pet data
     * @return array Formatted features
     */
    private function prepareFeatures($rawData) {
        // Kategorisasi Berat Badan
        $beratKg = floatval($rawData['berat_badan'] ?? 5);
        if ($beratKg < 5) {
            $beratKategori = 'Kecil';
        } elseif ($beratKg < 10) {
            $beratKategori = 'Sedang';
        } elseif ($beratKg < 20) {
            $beratKategori = 'Besar';
        } else {
            $beratKategori = 'Sangat Besar';
        }
        
        // Kategorisasi Usia
        $usiaBulan = intval($rawData['usia_bulan'] ?? 12);
        if ($usiaBulan < 6) {
            $usiaKategori = 'Bayi';
        } elseif ($usiaBulan < 12) {
            $usiaKategori = 'Muda';
        } elseif ($usiaBulan < 24) {
            $usiaKategori = 'Remaja';
        } elseif ($usiaBulan < 60) {
            $usiaKategori = 'Dewasa';
        } elseif ($usiaBulan < 120) {
            $usiaKategori = 'Tua';
        } else {
            $usiaKategori = 'Sangat Tua';
        }
        
        // Kategorisasi Frekuensi
        $frekuensi = intval($rawData['frekuensi_kunjungan'] ?? 1);
        if ($frekuensi < 2) {
            $frekuensiKategori = 'Jarang';
        } elseif ($frekuensi < 5) {
            $frekuensiKategori = 'Sedang';
        } else {
            $frekuensiKategori = 'Sering';
        }
        
        // Format features array sesuai urutan training
        // ['Jenis Hewan', 'Ras', 'Berat Badan', 'Usia', 'Frekuensi Kunjungan', 
        //  'Layanan Sering', 'Layanan Terakhir', 'Jenis Pakan', 'Merek Pakan', 'Hari Sejak Kunjungan']
        
        return [
            $rawData['jenis_hewan'] ?? 'Kucing',
            $rawData['ras'] ?? 'Domestik',
            $beratKategori,
            $usiaKategori,
            $frekuensiKategori,
            $rawData['layanan_sering'] ?? 'Grooming',
            $rawData['layanan_terakhir'] ?? 'Grooming',
            $rawData['jenis_pakan'] ?? 'Dry Food',
            $rawData['merek_pakan'] ?? 'Royal Canin',
            strval($rawData['hari_sejak_kunjungan'] ?? '30')
        ];
    }
    
    /**
     * Predict for User/Customer Role
     * Shows service recommendation with friendly message
     * 
     * @param array $petData Pet data
     * @return array Formatted result for user
     */
    public function predictForUser($petData) {
        $features = $this->prepareFeatures($petData);
        $result = $this->model->predictWithConfidence($features);
        
        // Create user-friendly message
        $messages = [
            'Grooming' => [
                'title' => '🐾 Waktunya Grooming!',
                'message' => 'Hewan peliharaan Anda mungkin membutuhkan perawatan grooming. Bulu yang terawat membuat hewan lebih sehat dan nyaman.',
                'action' => 'Booking Grooming',
                'icon' => '✂️'
            ],
            'Veterinary' => [
                'title' => '⚕️ Pemeriksaan Veterinary',
                'message' => 'Saatnya check-up kesehatan atau perawatan medis. Jaga kesehatan hewan kesayangan Anda!',
                'action' => 'Jadwalkan Pemeriksaan',
                'icon' => '🏥'
            ],
            'Vaksinasi' => [
                'title' => '💉 Jangan Lupa Vaksinasi!',
                'message' => 'Sudah waktunya vaksinasi untuk melindungi hewan peliharaan dari penyakit. Kesehatan adalah prioritas!',
                'action' => 'Jadwalkan Vaksinasi',
                'icon' => '🏥'
            ],
            'Pengobatan' => [
                'title' => '🏥 Konsultasi Kesehatan',
                'message' => 'Kami merekomendasikan pemeriksaan kesehatan. Lebih baik mencegah daripada mengobati!',
                'action' => 'Buat Janji Dokter',
                'icon' => '⚕️'
            ],
            'Pet Hotel' => [
                'title' => '🏨 Pet Hotel Ready!',
                'message' => 'Berencana bepergian? Titipkan hewan kesayangan di Pet Hotel kami yang nyaman dan aman.',
                'action' => 'Cek Ketersediaan',
                'icon' => '🛏️'
            ],
            'Konsultasi' => [
                'title' => '💬 Konsultasi Gratis',
                'message' => 'Ada yang ingin ditanyakan tentang hewan peliharaan Anda? Konsultasi gratis dengan dokter hewan kami!',
                'action' => 'Mulai Konsultasi',
                'icon' => '👨‍⚕️'
            ]
        ];
        
        $prediction = $result['prediction'];
        $info = $messages[$prediction] ?? [
            'title' => '🐾 Rekomendasi Layanan',
            'message' => "Kami merekomendasikan layanan: $prediction",
            'action' => 'Lihat Detail',
            'icon' => '📋'
        ];
        
        return [
            'role' => 'user',
            'prediction' => $prediction,
            'confidence' => $result['confidence'],
            'title' => $info['title'],
            'message' => $info['message'],
            'action_text' => $info['action'],
            'icon' => $info['icon'],
            'alternative_services' => $result['distribution'],
            'show_notification' => $result['confidence'] > 70
        ];
    }
    
    /**
     * Predict for Kasir Role
     * Shows upselling opportunities and promo suggestions
     * 
     * @param array $petData Pet data
     * @return array Formatted result for kasir
     */
    public function predictForKasir($petData) {
        $features = $this->prepareFeatures($petData);
        $result = $this->model->predictWithConfidence($features);
        
        $prediction = $result['prediction'];
        
        // Upselling suggestions based on prediction
        $upselling = [
            'Grooming' => [
                'main_service' => 'Grooming',
                'price_estimate' => 'Rp 75.000 - 150.000',
                'upsell_items' => [
                    'Paket Grooming Premium (+Spa)' => 'Rp 200.000',
                    'Parfum Pet' => 'Rp 35.000',
                    'Sisir & Gunting Kuku' => 'Rp 50.000'
                ],
                'promo' => 'Diskon 20% untuk paket grooming + pembelian pakan!'
            ],
            'Veterinary' => [
                'main_service' => 'Veterinary Check-up',
                'price_estimate' => 'Rp 100.000 - 400.000',
                'upsell_items' => [
                    'Paket Vaksinasi Lengkap' => 'Rp 250.000',
                    'Sterilisasi' => 'Rp 500.000',
                    'Vitamin & Suplemen' => 'Rp 85.000'
                ],
                'promo' => 'Gratis konsultasi + diskon 15% untuk paket lengkap!'
            ],
            'Vaksinasi' => [
                'main_service' => 'Vaksinasi',
                'price_estimate' => 'Rp 150.000 - 300.000',
                'upsell_items' => [
                    'Vitamin Booster' => 'Rp 75.000',
                    'Obat Cacing' => 'Rp 50.000',
                    'Kartu Kesehatan' => 'Rp 25.000'
                ],
                'promo' => 'Gratis konsultasi dokter untuk paket vaksinasi lengkap!'
            ],
            'Pengobatan' => [
                'main_service' => 'Pengobatan/Pemeriksaan',
                'price_estimate' => 'Rp 100.000 - 500.000',
                'upsell_items' => [
                    'Tes Laboratorium' => 'Rp 200.000',
                    'Obat & Vitamin' => 'Rp 100.000',
                    'Follow-up Visit (3x)' => 'Rp 250.000'
                ],
                'promo' => 'Paket pemeriksaan lengkap hemat 15%!'
            ],
            'Pet Hotel' => [
                'main_service' => 'Pet Hotel',
                'price_estimate' => 'Rp 50.000/hari',
                'upsell_items' => [
                    'Upgrade Kandang VIP' => 'Rp 100.000/hari',
                    'Daily Photo Update' => 'Rp 15.000/hari',
                    'Grooming saat checkout' => 'Rp 75.000'
                ],
                'promo' => 'Book 7 hari dapat 1 hari gratis + grooming!'
            ]
        ];
        
        $upsellInfo = $upselling[$prediction] ?? [
            'main_service' => $prediction,
            'price_estimate' => 'Hubungi kasir',
            'upsell_items' => [],
            'promo' => 'Tanyakan promo hari ini!'
        ];
        
        return [
            'role' => 'kasir',
            'prediction' => $prediction,
            'confidence' => $result['confidence'],
            'main_service' => $upsellInfo['main_service'],
            'price_estimate' => $upsellInfo['price_estimate'],
            'upsell_opportunities' => $upsellInfo['upsell_items'],
            'promo_suggestion' => $upsellInfo['promo'],
            'alternative_services' => $result['distribution'],
            'sales_script' => $this->generateSalesScript($prediction, $result['confidence'])
        ];
    }
    
    /**
     * Generate sales script for kasir
     */
    private function generateSalesScript($prediction, $confidence) {
        if ($confidence > 80) {
            return "\"Berdasarkan riwayat {petName}, sepertinya sudah waktunya untuk $prediction. Kami punya promo spesial hari ini!\"";
        } elseif ($confidence > 60) {
            return "\"Kami melihat {petName} mungkin membutuhkan $prediction. Mau saya buatkan jadwal?\"";
        } else {
            return "\"Untuk kesehatan {petName}, kami merekomendasikan $prediction. Tertarik untuk tahu lebih lanjut?\"";
        }
    }
    
    /**
     * Predict for Admin Role
     * Shows detailed analytics and model performance
     * 
     * @param array $petData Pet data (optional for stats)
     * @return array Formatted result for admin
     */
    public function predictForAdmin($petData = null) {
        $result = null;
        
        if ($petData !== null) {
            $features = $this->prepareFeatures($petData);
            $result = $this->model->predictWithConfidence($features);
        }
        
        return [
            'role' => 'admin',
            'model_info' => [
                'model_file' => basename($this->modelFile),
                'training_date' => $this->metadata['training_date'] ?? 'Unknown',
                'training_samples' => $this->metadata['training_samples'] ?? 0,
                'accuracy' => $this->metadata['accuracy'] ?? 0,
                'features_used' => $this->metadata['features'] ?? [],
                'target_classes' => $this->metadata['target_classes'] ?? []
            ],
            'feature_importance' => $this->metadata['feature_importance'] ?? [],
            'prediction_result' => $result,
            'recommendations' => [
                'Retrain model jika akurasi < 75%',
                'Update dataset dengan data terbaru setiap bulan',
                'Monitor prediksi yang salah untuk improvement'
            ]
        ];
    }
    
    /**
     * Predict for Dokter Role
     * Shows medical validation and health insights
     * 
     * @param array $petData Pet data
     * @return array Formatted result for dokter
     */
    public function predictForDokter($petData) {
        $features = $this->prepareFeatures($petData);
        $result = $this->model->predictWithConfidence($features);
        
        $prediction = $result['prediction'];
        
        // Medical context
        $medicalContext = [
            'Grooming' => [
                'health_note' => 'Grooming rutin mencegah masalah kulit dan parasit.',
                'check_points' => ['Kondisi kulit', 'Adanya kutu/jamur', 'Kondisi telinga'],
                'concern_level' => 'Low'
            ],
            'Vaksinasi' => [
                'health_note' => 'Vaksinasi wajib sesuai jadwal untuk imunitas optimal.',
                'check_points' => ['Riwayat vaksin', 'Kondisi kesehatan umum', 'Usia hewan'],
                'concern_level' => 'Medium'
            ],
            'Pengobatan' => [
                'health_note' => 'Pemeriksaan diperlukan untuk diagnosa akurat.',
                'check_points' => ['Gejala klinis', 'Riwayat penyakit', 'Tes laboratorium'],
                'concern_level' => 'High'
            ],
            'Pet Hotel' => [
                'health_note' => 'Pastikan hewan sehat sebelum dititipkan.',
                'check_points' => ['Vaksin up-to-date', 'Tidak ada gejala penyakit', 'Kondisi stres'],
                'concern_level' => 'Low'
            ]
        ];
        
        $context = $medicalContext[$prediction] ?? [
            'health_note' => 'Evaluasi kondisi hewan secara menyeluruh.',
            'check_points' => ['Kondisi umum'],
            'concern_level' => 'Medium'
        ];
        
        return [
            'role' => 'dokter',
            'prediction' => $prediction,
            'confidence' => $result['confidence'],
            'medical_validation' => [
                'ai_recommendation' => $prediction,
                'confidence_level' => $result['confidence'] > 75 ? 'High' : 'Medium',
                'health_note' => $context['health_note'],
                'check_points' => $context['check_points'],
                'concern_level' => $context['concern_level']
            ],
            'patient_history' => [
                'jenis_hewan' => $petData['jenis_hewan'] ?? '-',
                'usia_kategori' => $this->getAgeCategory($petData['usia_bulan'] ?? 0),
                'berat_badan' => $petData['berat_badan'] ?? '-',
                'frekuensi_kunjungan' => $petData['frekuensi_kunjungan'] ?? 0,
                'layanan_terakhir' => $petData['layanan_terakhir'] ?? '-'
            ],
            'alternative_diagnosis' => $result['distribution'],
            'recommendation' => 'Validasi prediksi AI dengan pemeriksaan fisik langsung'
        ];
    }
    
    /**
     * Helper: Get age category
     */
    private function getAgeCategory($usiaBulan) {
        if ($usiaBulan < 6) return 'Bayi (< 6 bulan)';
        if ($usiaBulan < 12) return 'Muda (6-12 bulan)';
        if ($usiaBulan < 24) return 'Remaja (1-2 tahun)';
        if ($usiaBulan < 60) return 'Dewasa (2-5 tahun)';
        if ($usiaBulan < 120) return 'Tua (5-10 tahun)';
        return 'Sangat Tua (>10 tahun)';
    }
    
    /**
     * Get model info
     */
    public function getModelInfo() {
        return $this->metadata;
    }
}
