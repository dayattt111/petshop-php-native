<?php
/**
 * Training Script for Decision Tree Model
 * Load data, preprocess, train, and save model
 * 
 * @author Xboxpetshop Development Team
 */

require_once __DIR__ . '/DecisionTree.php';
require_once __DIR__ . '/DataPreprocessor.php';

/**
 * Main training function
 */
function trainModel($csvFile, $modelOutputFile) {
    echo "=== XBOXPETSHOP - DECISION TREE TRAINING ===\n\n";
    
    try {
        // 1. Load and preprocess data
        echo "[1/6] Loading data from CSV...\n";
        $preprocessor = new DataPreprocessor();
        $preprocessor->loadCSV($csvFile, true);
        
        echo "     Data loaded: " . count($preprocessor->getData()) . " rows\n\n";
        
        // 2. Data cleaning
        echo "[2/6] Cleaning data...\n";
        $affected = $preprocessor->handleMissingValues('fill_mode');
        echo "     Missing values handled: $affected cells\n";
        
        $preprocessor->normalizeText();
        echo "     Text normalized\n\n";
        
        // 3. Feature engineering
        echo "[3/6] Feature engineering...\n";
        
        // Kategorisasi Berat Badan
        try {
            $weightInfo = $preprocessor->categorizeWeight('Berat Badan (Kg)', 'general');
            echo "     Berat Badan categorized: " . implode(', ', $weightInfo['labels']) . "\n";
        } catch (Exception $e) {
            echo "     Warning: Could not categorize Berat Badan - " . $e->getMessage() . "\n";
        }
        
        // Kategorisasi Usia
        try {
            $ageInfo = $preprocessor->categorizeAge('Usia (Bulan)', 'bulan');
            echo "     Usia categorized: " . implode(', ', $ageInfo['labels']) . "\n";
        } catch (Exception $e) {
            echo "     Warning: Could not categorize Usia - " . $e->getMessage() . "\n";
        }
        
        // Kategorisasi Frekuensi Kunjungan
        try {
            $freqInfo = $preprocessor->autoDiscretize('Frekuensi Kunjungan', 3, ['Jarang', 'Sedang', 'Sering']);
            echo "     Frekuensi Kunjungan categorized\n";
        } catch (Exception $e) {
            echo "     Warning: Could not categorize Frekuensi - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
        
        // 4. Split features and target
        echo "[4/6] Preparing training data...\n";
        $dataset = $preprocessor->splitFeaturesTarget('Kebutuhan Layanan Berikutnya');
        
        $features = $dataset['features'];
        $target = $dataset['target'];
        $featureNames = $dataset['feature_names'];
        
        echo "     Features: " . count($features) . " samples x " . count($featureNames) . " features\n";
        echo "     Target classes: " . count(array_unique($target)) . " classes\n";
        echo "     Classes: " . implode(', ', array_unique($target)) . "\n\n";
        
        // 5. Train model
        echo "[5/6] Training Decision Tree model...\n";
        $startTime = microtime(true);
        
        $model = new DecisionTree(
            maxDepth: 10,          // Maximum depth of tree
            minSamplesLeaf: 2      // Minimum samples in leaf node
        );
        
        $model->fit($features, $target, $featureNames);
        
        $endTime = microtime(true);
        $trainingTime = round($endTime - $startTime, 2);
        
        echo "     Model trained in {$trainingTime} seconds\n\n";
        
        // 6. Evaluate and save
        echo "[6/6] Evaluating and saving model...\n";
        
        // Calculate accuracy on training data
        $accuracy = $model->calculateAccuracy($features, $target);
        echo "     Training Accuracy: " . round($accuracy, 2) . "%\n";
        
        // Get feature importance
        $importance = $model->getFeatureImportance($featureNames);
        echo "     Feature Importance:\n";
        $count = 0;
        foreach ($importance as $feature => $score) {
            if ($count++ < 5) { // Show top 5
                echo "       - $feature: $score%\n";
            }
        }
        
        // Save model
        $model->saveModel($modelOutputFile);
        echo "\n     Model saved to: $modelOutputFile\n";
        
        // Save metadata
        $metadata = [
            'training_date' => date('Y-m-d H:i:s'),
            'training_samples' => count($features),
            'features' => $featureNames,
            'target_classes' => array_values(array_unique($target)),
            'accuracy' => round($accuracy, 2),
            'feature_importance' => $importance,
            'training_time_seconds' => $trainingTime
        ];
        
        $metadataFile = str_replace('.json', '_metadata.json', $modelOutputFile);
        file_put_contents($metadataFile, json_encode($metadata, JSON_PRETTY_PRINT));
        echo "     Metadata saved to: $metadataFile\n";
        
        echo "\n=== TRAINING COMPLETED SUCCESSFULLY ===\n";
        
        return [
            'success' => true,
            'model_file' => $modelOutputFile,
            'metadata_file' => $metadataFile,
            'accuracy' => $accuracy
        ];
        
    } catch (Exception $e) {
        echo "\n!!! ERROR: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
        
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * Demo: Test prediction with sample data
 */
function testPrediction($modelFile) {
    echo "\n=== TESTING PREDICTION ===\n\n";
    
    try {
        $model = new DecisionTree();
        $model->loadModel($modelFile);
        
        // Sample test data
        $testSamples = [
            [
                'name' => 'Kucing Persia, 4kg, 18 bulan, sering ke klinik',
                'data' => ['Kucing', 'Persia', 'Normal', 'Remaja', 'Sering', 'Grooming', 'Grooming', 'Whiskas Premium', 'Whiskas', '30']
            ],
            [
                'name' => 'Anjing Golden, 25kg, 5 tahun, jarang ke klinik',
                'data' => ['Anjing', 'Golden Retriever', 'Besar', 'Dewasa', 'Jarang', 'Vaksinasi', 'Vaksinasi', 'Pedigree Adult', 'Pedigree', '180']
            ]
        ];
        
        foreach ($testSamples as $sample) {
            echo "Test: {$sample['name']}\n";
            $result = $model->predictWithConfidence($sample['data']);
            echo "  → Prediction: {$result['prediction']}\n";
            echo "  → Confidence: {$result['confidence']}%\n";
            echo "  → Distribution: " . json_encode($result['distribution']) . "\n\n";
        }
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// ============================================
// MAIN EXECUTION
// ============================================

// Check if running from command line
if (php_sapi_name() === 'cli') {
    $csvFile = $argv[1] ?? __DIR__ . '/data_training.csv';
    $modelFile = $argv[2] ?? __DIR__ . '/model_petshop.json';
    
    $result = trainModel($csvFile, $modelFile);
    
    if ($result['success']) {
        // Run test prediction
        testPrediction($modelFile);
    }
    
} else {
    // Running from web browser
    echo "<pre>";
    
    $csvFile = __DIR__ . '/data_training.csv';
    $modelFile = __DIR__ . '/model_petshop.json';
    
    if (isset($_GET['action']) && $_GET['action'] === 'train') {
        $result = trainModel($csvFile, $modelFile);
        
        if ($result['success']) {
            testPrediction($modelFile);
        }
    } else {
        echo "=== XBOXPETSHOP ML TRAINING ===\n\n";
        echo "To train the model, visit: ?action=train\n";
        echo "Or run from command line: php train_model.php\n";
    }
    
    echo "</pre>";
}
