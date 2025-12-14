<?php
/**
 * Admin Dashboard - ML Model Management
 * Manage training, view accuracy, and model performance
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
    $adminResult = $predictionService->predictForAdmin();
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Handle training request
if (isset($_POST['train_model'])) {
    header('Location: ../ml_prediction/train_model.php?action=train');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Model Management - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        .model-info-card {
            border-left: 5px solid #007bff;
            background: #f8f9fa;
        }
        .accuracy-display {
            font-size: 3rem;
            font-weight: bold;
            color: #28a745;
        }
        .feature-importance-bar {
            margin: 10px 0;
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
                <h3>🤖 Machine Learning Model Management</h3>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <h5>⚠️ Model Not Found</h5>
                        <p><?= htmlspecialchars($error) ?></p>
                        <form method="post">
                            <button type="submit" name="train_model" class="btn btn-primary">
                                🚀 Train Model Now
                            </button>
                        </form>
                    </div>
                <?php elseif (isset($adminResult)): ?>
                    
                    <!-- Model Overview -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="text-muted">Model Accuracy</h6>
                                    <div class="accuracy-display">
                                        <?= $adminResult['model_info']['accuracy'] ?>%
                                    </div>
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
                    
                    <!-- Model Details -->
                    <div class="card model-info-card mb-4">
                        <div class="card-header">
                            <strong>📋 Model Information</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Model File:</strong> <?= $adminResult['model_info']['model_file'] ?></p>
                                    <p><strong>Training Date:</strong> <?= $adminResult['model_info']['training_date'] ?></p>
                                    <p><strong>Algorithm:</strong> Decision Tree (C4.5)</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Target Classes:</strong></p>
                                    <ul>
                                        <?php foreach ($adminResult['model_info']['target_classes'] as $class): ?>
                                            <li><?= htmlspecialchars($class) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Feature Importance -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <strong>📊 Feature Importance</strong>
                        </div>
                        <div class="card-body">
                            <?php foreach ($adminResult['feature_importance'] as $feature => $importance): ?>
                                <div class="feature-importance-bar">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><?= htmlspecialchars($feature) ?></span>
                                        <strong><?= $importance ?>%</strong>
                                    </div>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-info" 
                                             style="width: <?= $importance ?>%"
                                             role="progressbar">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Features Used -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <strong>🔧 Features Used in Model</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($adminResult['model_info']['features_used'] as $idx => $feature): ?>
                                    <div class="col-md-4">
                                        <span class="badge bg-secondary"><?= $idx + 1 ?>. <?= htmlspecialchars($feature) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recommendations -->
                    <div class="card border-warning mb-4">
                        <div class="card-header bg-warning">
                            <strong>💡 Recommendations</strong>
                        </div>
                        <div class="card-body">
                            <ul>
                                <?php foreach ($adminResult['recommendations'] as $recommendation): ?>
                                    <li><?= htmlspecialchars($recommendation) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="card">
                        <div class="card-header">
                            <strong>⚙️ Model Actions</strong>
                        </div>
                        <div class="card-body">
                            <form method="post" class="d-inline">
                                <button type="submit" name="train_model" class="btn btn-primary">
                                    🔄 Retrain Model
                                </button>
                            </form>
                            <a href="../ml_prediction/Dataset_Hewan_Petshop_Makassar_2024.csv" class="btn btn-success" download>
                                📥 Download Training Data
                            </a>
                            <a href="../ml_prediction/model_petshop.json" class="btn btn-info" download>
                                💾 Download Model
                            </a>
                            <button class="btn btn-warning" onclick="if(confirm('Upload dataset baru?')) alert('Feature coming soon!')">
                                📤 Upload New Dataset
                            </button>
                        </div>
                    </div>
                    
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
