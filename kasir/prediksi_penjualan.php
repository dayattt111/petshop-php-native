<?php
/**
 * Prediction Page - Kasir Role
 * Shows upselling opportunities and sales recommendations
 */

session_start();
require_once __DIR__ . '/../ml_prediction/PredictionService.php';
require_once __DIR__ . '/../config/koneksi.php';

// Check if kasir is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    header('Location: ../login.php');
    exit;
}

// Get customer/pet ID from request
$petId = $_GET['pet_id'] ?? null;

// Sample data - in production, fetch from database
$petData = [
    'pet_name' => 'Milo',
    'customer_name' => 'Bu Sari',
    'jenis_hewan' => 'Anjing',
    'ras' => 'Golden Retriever',
    'berat_badan' => 25,
    'usia_bulan' => 36,
    'frekuensi_kunjungan' => 8,
    'layanan_sering' => 'Grooming',
    'layanan_terakhir' => 'Grooming',
    'jenis_pakan' => 'Pedigree Adult',
    'merek_pakan' => 'Pedigree',
    'hari_sejak_kunjungan' => 45
];

try {
    $predictionService = new PredictionService();
    $result = $predictionService->predictForKasir($petData);
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Assistant - Kasir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        .sales-dashboard {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
        .prediction-badge {
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-size: 1.3rem;
            margin-bottom: 20px;
        }
        .upsell-item {
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin: 10px 0;
            background: white;
            border-radius: 5px;
        }
        .sales-script {
            background: #17a2b8;
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-style: italic;
        }
        .promo-box {
            background: linear-gradient(45deg, #ff6b6b, #ee5a6f);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3">
                <?php include 'includes/sidebar.php'; ?>
            </div>
            
            <div class="col-md-9">
                <h3>💰 Sales Assistant - AI Recommendation</h3>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                    </div>
                <?php elseif (isset($result)): ?>
                    
                    <div class="sales-dashboard">
                        <!-- Customer Info -->
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <strong>👤 Customer Check-in</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Customer:</strong> <?= htmlspecialchars($petData['customer_name']) ?></p>
                                        <p><strong>Pet:</strong> <?= htmlspecialchars($petData['pet_name']) ?> 
                                           (<?= htmlspecialchars($petData['jenis_hewan']) ?> - <?= htmlspecialchars($petData['ras']) ?>)</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Berat:</strong> <?= $petData['berat_badan'] ?> kg</p>
                                        <p><strong>Kunjungan ke-:</strong> <?= $petData['frekuensi_kunjungan'] ?></p>
                                        <p><strong>Terakhir:</strong> <?= $petData['hari_sejak_kunjungan'] ?> hari lalu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- AI Prediction -->
                        <div class="prediction-badge">
                            🎯 AI Prediction: <strong><?= $result['prediction'] ?></strong>
                            (<?= $result['confidence'] ?>% confidence)
                        </div>
                        
                        <!-- Sales Script -->
                        <div class="sales-script mb-3">
                            💬 <strong>Script Penjualan:</strong><br>
                            <?= str_replace('{petName}', $petData['pet_name'], $result['sales_script']) ?>
                        </div>
                        
                        <!-- Main Service -->
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <strong>💼 Layanan Utama</strong>
                            </div>
                            <div class="card-body">
                                <h5><?= $result['main_service'] ?></h5>
                                <p class="text-muted">Estimasi Harga: <strong><?= $result['price_estimate'] ?></strong></p>
                            </div>
                        </div>
                        
                        <!-- Upselling Opportunities -->
                        <div class="card mb-3">
                            <div class="card-header bg-warning">
                                <strong>⬆️ Upselling Opportunities</strong>
                            </div>
                            <div class="card-body">
                                <?php foreach ($result['upsell_opportunities'] as $item => $price): ?>
                                    <div class="upsell-item">
                                        <div class="d-flex justify-content-between">
                                            <span>✓ <?= htmlspecialchars($item) ?></span>
                                            <strong><?= htmlspecialchars($price) ?></strong>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Promo -->
                        <div class="promo-box">
                            <h5>🎁 Promo Hari Ini</h5>
                            <p class="mb-0"><?= $result['promo_suggestion'] ?></p>
                        </div>
                        
                        <!-- Alternative Services -->
                        <div class="card">
                            <div class="card-header">
                                <strong>📊 Kemungkinan Layanan Lain</strong>
                            </div>
                            <div class="card-body">
                                <?php foreach ($result['alternative_services'] as $service => $percentage): ?>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span><?= htmlspecialchars($service) ?></span>
                                            <span><?= $percentage ?>%</span>
                                        </div>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar 
                                                <?= $service === $result['prediction'] ? 'bg-success' : 'bg-secondary' ?>" 
                                                 style="width: <?= $percentage ?>%">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-4 text-center">
                            <button class="btn btn-success btn-lg" onclick="processTransaction()">
                                ✓ Proses Transaksi
                            </button>
                            <button class="btn btn-info btn-lg" onclick="printRecommendation()">
                                🖨️ Print Rekomendasi
                            </button>
                        </div>
                    </div>
                    
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function processTransaction() {
            if (confirm('Proses transaksi dengan layanan yang direkomendasikan?')) {
                // Redirect ke halaman transaksi
                window.location.href = 'transaksi.php?service=<?= urlencode($result['prediction'] ?? '') ?>';
            }
        }
        
        function printRecommendation() {
            window.print();
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
