<?php
/**
 * Admin ML Preview Dashboard
 * View all ML interfaces from different roles in one page
 */

session_start();
require_once __DIR__ . '/../ml_prediction/PredictionService.php';

// Check if admin is logged in
if (!isset($_SESSION['username_212238']) || $_SESSION['role_212238'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

try {
    $predictionService = new PredictionService();
    
    // Sample data untuk preview masing-masing role
    $sampleDataKasir = [
        'pet_name' => 'Luna',
        'customer_name' => 'Ibu Sari',
        'jenis_hewan' => 'Kucing',
        'ras' => 'Maine Coon',
        'berat_badan' => 5.6,
        'usia_bulan' => 18,
        'frekuensi_kunjungan' => 2,
        'layanan_sering' => 'Grooming',
        'layanan_terakhir' => 'Pet Hotel',
        'jenis_pakan' => 'Kering',
        'merek_pakan' => 'OriCat',
        'hari_sejak_kunjungan' => 45
    ];
    
    $sampleDataDokter = [
        'pet_name' => 'Bella',
        'customer_name' => 'Bapak Andi',
        'jenis_hewan' => 'Kucing',
        'ras' => 'Persia',
        'berat_badan' => 6.1,
        'usia_bulan' => 60,
        'frekuensi_kunjungan' => 3,
        'layanan_sering' => 'Veterinary',
        'layanan_terakhir' => 'Grooming',
        'jenis_pakan' => 'Basah',
        'merek_pakan' => 'Whiskas',
        'hari_sejak_kunjungan' => 120
    ];
    
    $sampleDataUser = [
        'pet_name' => 'Milo',
        'jenis_hewan' => 'Kucing',
        'ras' => 'Persia',
        'berat_badan' => 4.5,
        'usia_bulan' => 18,
        'frekuensi_kunjungan' => 2,
        'layanan_sering' => 'Grooming',
        'layanan_terakhir' => 'Grooming',
        'jenis_pakan' => 'Kering',
        'merek_pakan' => 'Royal Canin',
        'hari_sejak_kunjungan' => 30
    ];
    
    // Get predictions
    $kasirResult = $predictionService->predictForKasir($sampleDataKasir);
    $dokterResult = $predictionService->predictForDokter($sampleDataDokter);
    $userResult = $predictionService->predictForUser($sampleDataUser);
    $adminResult = $predictionService->predictForAdmin();
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML System Preview - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .preview-card {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
        }
        .role-badge {
            font-size: 1.2rem;
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 15px;
        }
        .badge-kasir { background: #28a745; color: white; }
        .badge-dokter { background: #dc3545; color: white; }
        .badge-user { background: #17a2b8; color: white; }
        .badge-admin { background: #ffc107; color: black; }
        
        .tab-content {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
        }
        .nav-tabs .nav-link {
            font-weight: 600;
            color: #495057;
        }
        .nav-tabs .nav-link.active {
            background: #f8f9fa;
            border-bottom: 3px solid #007bff;
        }
        
        /* Copy styles from each role interface */
        .upsell-item {
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin: 10px 0;
            background: white;
            border-radius: 5px;
        }
        .medical-card {
            border-left: 5px solid #dc3545;
        }
        .concern-high { color: #dc3545; font-weight: bold; }
        .concern-medium { color: #ffc107; font-weight: bold; }
        .concern-low { color: #28a745; font-weight: bold; }
        .user-service-card {
            border: 2px solid #17a2b8;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <?php $mode = 'main'; include 'includes/sidebar.php'; ?>
            
            <div class="col-md-9" style="margin-left: 270px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3><i class="bi bi-eye"></i> ML System Preview - All Roles</h3>
                    <span class="badge bg-warning text-dark">Admin View</span>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                    </div>
                <?php else: ?>
                
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="roleTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button">
                            <i class="bi bi-cpu"></i> Admin Dashboard
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="kasir-tab" data-bs-toggle="tab" data-bs-target="#kasir" type="button">
                            <i class="bi bi-graph-up-arrow"></i> Kasir View
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="dokter-tab" data-bs-toggle="tab" data-bs-target="#dokter" type="button">
                            <i class="bi bi-capsule"></i> Dokter View
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button">
                            <i class="bi bi-heart-pulse"></i> User View
                        </button>
                    </li>
                </ul>
                
                <!-- Tabs Content -->
                <div class="tab-content" id="roleTabsContent">
                    
                    <!-- ADMIN TAB -->
                    <div class="tab-pane fade show active" id="admin" role="tabpanel">
                        <div class="preview-card">
                            <span class="role-badge badge-admin">👨‍💼 ADMIN - ML Management Dashboard</span>
                            
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h6 class="text-muted">Model Accuracy</h6>
                                            <h2 class="text-success"><?= $adminResult['model_info']['accuracy'] ?>%</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h6 class="text-muted">Training Samples</h6>
                                            <h2><?= $adminResult['model_info']['training_samples'] ?></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h6 class="text-muted">Features</h6>
                                            <h2><?= count($adminResult['model_info']['features_used']) ?></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h6 class="text-muted">Target Classes</h6>
                                            <h2><?= count($adminResult['model_info']['target_classes']) ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-header"><strong>📊 Feature Importance</strong></div>
                                <div class="card-body">
                                    <?php foreach (array_slice($adminResult['feature_importance'], 0, 5) as $feature => $importance): ?>
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span><?= htmlspecialchars($feature) ?></span>
                                                <strong><?= $importance ?>%</strong>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-info" style="width: <?= $importance ?>%"></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <strong>ℹ️ What Admin Sees:</strong> Model performance metrics, accuracy statistics, feature importance rankings, and system recommendations for business optimization.
                            </div>
                        </div>
                    </div>
                    
                    <!-- KASIR TAB -->
                    <div class="tab-pane fade" id="kasir" role="tabpanel">
                        <div class="preview-card">
                            <span class="role-badge badge-kasir">💰 KASIR - Sales Assistant</span>
                            
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <strong>👤 Customer: <?= htmlspecialchars($sampleDataKasir['customer_name']) ?></strong>
                                </div>
                                <div class="card-body">
                                    <p><strong>Pet:</strong> <?= $sampleDataKasir['pet_name'] ?> (<?= $sampleDataKasir['jenis_hewan'] ?> - <?= $sampleDataKasir['ras'] ?>)</p>
                                    <h4 class="text-success">AI Prediction: <?= $kasirResult['prediction'] ?></h4>
                                    <p class="text-muted">Confidence: <?= $kasirResult['confidence'] ?>%</p>
                                </div>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <strong>💡 Sales Script</strong>
                                </div>
                                <div class="card-body">
                                    <p class="fst-italic"><?= nl2br($kasirResult['sales_script']) ?></p>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header bg-warning">
                                    <strong>🎁 Upselling Opportunities</strong>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($kasirResult['upselling_items'] as $item): ?>
                                        <div class="upsell-item">
                                            <strong><?= $item['item'] ?></strong> - Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                            <p class="mb-0 text-muted"><?= $item['reason'] ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="alert alert-success mt-3">
                                <strong>ℹ️ What Kasir Sees:</strong> AI-powered sales recommendations, smart upselling suggestions with prices, and ready-to-use sales scripts to maximize revenue per transaction.
                            </div>
                        </div>
                    </div>
                    
                    <!-- DOKTER TAB -->
                    <div class="tab-pane fade" id="dokter" role="tabpanel">
                        <div class="preview-card">
                            <span class="role-badge badge-dokter">⚕️ DOKTER - Medical AI Assistant</span>
                            
                            <div class="card medical-card mb-3">
                                <div class="card-header bg-danger text-white">
                                    <strong>👤 Patient: <?= htmlspecialchars($sampleDataDokter['pet_name']) ?></strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Owner:</strong> <?= $sampleDataDokter['customer_name'] ?></p>
                                            <p><strong>Species:</strong> <?= $dokterResult['patient_history']['jenis_hewan'] ?></p>
                                            <p><strong>Breed:</strong> <?= $dokterResult['patient_history']['ras'] ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Age:</strong> <?= $dokterResult['patient_history']['usia_kategori'] ?></p>
                                            <p><strong>Weight:</strong> <?= $dokterResult['patient_history']['berat_badan'] ?> kg</p>
                                            <p><strong>Last Visit:</strong> <?= $dokterResult['patient_history']['hari_sejak_kunjungan'] ?> days ago</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <strong>🤖 AI Recommendation</strong>
                                </div>
                                <div class="card-body">
                                    <h4><?= $dokterResult['prediction'] ?></h4>
                                    <p>Confidence: <?= $dokterResult['confidence'] ?>%</p>
                                    <p><strong>Concern Level:</strong> 
                                        <span class="concern-<?= strtolower($dokterResult['medical_validation']['concern_level']) ?>">
                                            <?= $dokterResult['medical_validation']['concern_level'] ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <strong>✅ Health Check Points</strong>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($dokterResult['health_checks'] as $check): ?>
                                        <div class="alert alert-light">
                                            <strong><?= $check['check'] ?></strong>
                                            <p class="mb-0"><?= $check['detail'] ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="alert alert-danger mt-3">
                                <strong>ℹ️ What Dokter Sees:</strong> AI medical predictions with confidence levels, patient history analysis, health check point recommendations, and medical validation notes for clinical decision support.
                            </div>
                        </div>
                    </div>
                    
                    <!-- USER TAB -->
                    <div class="tab-pane fade" id="user" role="tabpanel">
                        <div class="preview-card">
                            <span class="role-badge badge-user">👤 USER - Service Recommendation</span>
                            
                            <div class="card user-service-card mb-3">
                                <div class="card-header bg-info text-white">
                                    <strong>🐾 Your Pet: <?= htmlspecialchars($sampleDataUser['pet_name']) ?></strong>
                                </div>
                                <div class="card-body">
                                    <p><strong>Type:</strong> <?= $userResult['pet_info']['jenis_hewan'] ?> - <?= $userResult['pet_info']['ras'] ?></p>
                                    <p><strong>Age:</strong> <?= $userResult['pet_info']['usia_kategori'] ?></p>
                                    <p><strong>Weight:</strong> <?= $userResult['pet_info']['berat_badan'] ?> kg</p>
                                </div>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <strong>💡 Recommended Service</strong>
                                </div>
                                <div class="card-body text-center">
                                    <h3 class="text-success"><?= $userResult['prediction'] ?></h3>
                                    <p class="badge bg-success">Confidence: <?= $userResult['confidence'] ?>%</p>
                                    <hr>
                                    <p><?= $userResult['message'] ?></p>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <strong>📅 Next Actions</strong>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($userResult['action_buttons'] as $action): ?>
                                        <button class="btn btn-outline-primary btn-sm mb-2 w-100">
                                            <?= $action ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <?php if (!empty($userResult['alternative_services'])): ?>
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <strong>🔄 Alternative Services</strong>
                                    </div>
                                    <div class="card-body">
                                        <?php foreach ($userResult['alternative_services'] as $service): ?>
                                            <div class="mb-2">
                                                <strong><?= $service['service'] ?></strong>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: <?= $service['probability'] ?>%">
                                                        <?= $service['probability'] ?>%
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="alert alert-info mt-3">
                                <strong>ℹ️ What User Sees:</strong> User-friendly service recommendations with next action buttons, alternative service suggestions, and pet care reminders - all in simple language for pet owners.
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Summary Card -->
                <div class="card mt-4 border-warning">
                    <div class="card-header bg-warning">
                        <strong>📊 Preview Summary</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h5>Admin</h5>
                                    <p class="small">Model management & analytics</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h5>Kasir</h5>
                                    <p class="small">Sales optimization & upselling</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h5>Dokter</h5>
                                    <p class="small">Medical validation & diagnosis</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h5>User</h5>
                                    <p class="small">Service recommendations</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <p class="text-center mb-0">
                            <strong>💡 Tip:</strong> Each role sees different information based on their needs. 
                            Admin can access all interfaces through sidebar menu.
                        </p>
                    </div>
                </div>
                
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
