<?php
/**
 * Prediction Page - User/Customer Role
 * Shows service recommendations for pet owners
 */

session_start();
require_once __DIR__ . '/../ml_prediction/PredictionService.php';
require_once __DIR__ . '/../config/koneksi.php';

// Check if user or admin is logged in
if (!isset($_SESSION['username_212238']) || !in_array($_SESSION['role_212238'], ['user', 'admin'])) {
    header('Location: ../login.php');
    exit;
}

$username = $_SESSION['nama_212238'];

// Sample pet data - sesuai dataset Makassar
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

try {
    $predictionService = new PredictionService();
    $result = $predictionService->predictForUser($petData);
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Layanan - Xboxpetshop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        .prediction-card {
            border: 2px solid #4CAF50;
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .confidence-badge {
            font-size: 1.5rem;
            padding: 10px 20px;
            border-radius: 50px;
            background: rgba(255,255,255,0.3);
        }
        .icon-large {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        .alternative-services {
            background: white;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container mt-5">
        <h2 class="mb-4">🐾 Rekomendasi Layanan untuk Hewan Anda</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                <br><small>Pastikan model sudah di-training. Akses: <a href="../ml_prediction/train_model.php?action=train">Train Model</a></small>
            </div>
        <?php elseif (isset($result)): ?>
            
            <!-- Main Prediction Card -->
            <div class="prediction-card text-center">
                <div class="icon-large"><?= $result['icon'] ?></div>
                <h3><?= $result['title'] ?></h3>
                <p class="lead"><?= $result['message'] ?></p>
                
                <div class="my-4">
                    <span class="confidence-badge">
                        ✓ <?= $result['confidence'] ?>% Confidence
                    </span>
                </div>
                
                <a href="#" class="btn btn-light btn-lg">
                    <?= $result['action_text'] ?> →
                </a>
            </div>
            
            <!-- Alternative Services -->
            <div class="alternative-services">
                <h5>📊 Layanan Lain yang Mungkin Anda Butuhkan:</h5>
                <div class="row mt-3">
                    <?php foreach ($result['alternative_services'] as $service => $percentage): ?>
                        <?php if ($service !== $result['prediction']): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6><?= htmlspecialchars($service) ?></h6>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" 
                                                 style="width: <?= $percentage ?>%">
                                                <?= $percentage ?>%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Information Box -->
            <div class="alert alert-info mt-4">
                <h6>ℹ️ Bagaimana Kami Memberikan Rekomendasi?</h6>
                <p class="mb-0">
                    Sistem kami menggunakan <strong>Artificial Intelligence (Decision Tree)</strong> 
                    untuk menganalisis riwayat kunjungan, jenis hewan, usia, dan pola perawatan 
                    hewan peliharaan Anda untuk memberikan rekomendasi layanan yang tepat.
                </p>
            </div>
            
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
