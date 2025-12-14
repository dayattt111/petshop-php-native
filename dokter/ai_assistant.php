<?php
/**
 * Doctor Dashboard - Medical AI Validation
 * Validate AI predictions with medical expertise
 */

session_start();
require_once __DIR__ . '/../ml_prediction/PredictionService.php';

// Check if dokter is logged in
if (!isset($_SESSION['username_212238']) || $_SESSION['role_212238'] !== 'dokter') {
    header('Location: ../login.php');
    exit;
}

// Sample patient data - sesuai dataset Makassar
$petData = [
    'pet_name' => 'Bella',
    'customer_name' => 'Bapak Ahmad',
    'jenis_hewan' => 'Kucing',
    'ras' => 'Persia',
    'berat_badan' => 6.1,
    'usia_bulan' => 60,
    'frekuensi_kunjungan' => 3,
    'layanan_sering' => 'Veterinary',
    'layanan_terakhir' => 'Veterinary',
    'jenis_pakan' => 'Kering',
    'merek_pakan' => 'Whiskas',
    'hari_sejak_kunjungan' => 120
];

try {
    $predictionService = new PredictionService();
    $result = $predictionService->predictForDokter($petData);
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Medical Assistant - Dokter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        .medical-card {
            border-left: 5px solid #dc3545;
        }
        .ai-recommendation {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .concern-high { color: #dc3545; font-weight: bold; }
        .concern-medium { color: #ffc107; font-weight: bold; }
        .concern-low { color: #28a745; font-weight: bold; }
        .check-point {
            background: #f8f9fa;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border-left: 3px solid #17a2b8;
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
                <h3>⚕️ AI Medical Assistant</h3>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php elseif (isset($result)): ?>
                    
                    <!-- Patient Info -->
                    <div class="card medical-card mb-3">
                        <div class="card-header bg-danger text-white">
                            <strong>👤 Patient Information</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Patient:</strong> <?= htmlspecialchars($petData['pet_name']) ?></p>
                                    <p><strong>Owner:</strong> <?= htmlspecialchars($petData['customer_name']) ?></p>
                                    <p><strong>Species:</strong> <?= $result['patient_history']['jenis_hewan'] ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Age:</strong> <?= $result['patient_history']['usia_kategori'] ?></p>
                                    <p><strong>Weight:</strong> <?= $result['patient_history']['berat_badan'] ?> kg</p>
                                    <p><strong>Visit Count:</strong> <?= $result['patient_history']['frekuensi_kunjungan'] ?> times</p>
                                    <p><strong>Last Service:</strong> <?= $result['patient_history']['layanan_terakhir'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- AI Recommendation -->
                    <div class="ai-recommendation">
                        <h4>🤖 AI Recommendation</h4>
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3><?= $result['prediction'] ?></h3>
                                <p class="mb-0">Confidence Level: 
                                    <strong><?= $result['medical_validation']['confidence_level'] ?></strong>
                                    (<?= $result['confidence'] ?>%)
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge bg-light text-dark" style="font-size: 1.2rem;">
                                    Concern: 
                                    <span class="concern-<?= strtolower($result['medical_validation']['concern_level']) ?>">
                                        <?= $result['medical_validation']['concern_level'] ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Medical Validation -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <strong>🩺 Medical Validation Required</strong>
                        </div>
                        <div class="card-body">
                            <h6>Health Note:</h6>
                            <p><?= $result['medical_validation']['health_note'] ?></p>
                            
                            <h6 class="mt-3">Check Points untuk Validasi:</h6>
                            <?php foreach ($result['medical_validation']['check_points'] as $point): ?>
                                <div class="check-point">
                                    ✓ <?= htmlspecialchars($point) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Alternative Diagnosis -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>🔍 Alternative Considerations</strong>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Based on data patterns, other possible needs:</p>
                            <?php foreach ($result['alternative_diagnosis'] as $diagnosis => $probability): ?>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span><?= htmlspecialchars($diagnosis) ?></span>
                                        <span><?= $probability ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar 
                                            <?= $diagnosis === $result['prediction'] ? 'bg-danger' : 'bg-secondary' ?>" 
                                             style="width: <?= $probability ?>%">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Doctor's Validation Form -->
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <strong>✍️ Doctor's Validation</strong>
                        </div>
                        <div class="card-body">
                            <form method="post" action="proses/validate_prediction.php">
                                <div class="mb-3">
                                    <label class="form-label">AI Prediction Accurate?</label>
                                    <div>
                                        <input type="radio" name="validation" value="agree" id="agree" required>
                                        <label for="agree">✓ Yes, I agree with AI recommendation</label>
                                    </div>
                                    <div>
                                        <input type="radio" name="validation" value="disagree" id="disagree">
                                        <label for="disagree">✗ No, I have different diagnosis</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Your Diagnosis:</label>
                                    <select name="doctor_diagnosis" class="form-select" required>
                                        <option value="">-- Select Service --</option>
                                        <option value="Grooming">Grooming</option>
                                        <option value="Vaksinasi">Vaksinasi</option>
                                        <option value="Pengobatan">Pengobatan</option>
                                        <option value="Pet Hotel">Pet Hotel</option>
                                        <option value="Konsultasi">Konsultasi</option>
                                        <option value="Emergency">Emergency</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Medical Notes:</label>
                                    <textarea name="medical_notes" class="form-control" rows="4" required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Treatment Plan:</label>
                                    <textarea name="treatment_plan" class="form-control" rows="3" required></textarea>
                                </div>
                                
                                <div class="alert alert-info">
                                    <strong>💡 Recommendation:</strong> <?= $result['recommendation'] ?>
                                </div>
                                
                                <button type="submit" class="btn btn-success btn-lg">
                                    ✓ Submit Validation & Create Medical Record
                                </button>
                            </form>
                        </div>
                    </div>
                    
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
