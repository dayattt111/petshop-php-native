<?php
/**
 * Quick Test Script
 * Test the Decision Tree implementation
 */

require_once __DIR__ . '/DecisionTree.php';
require_once __DIR__ . '/DataPreprocessor.php';

echo "=== QUICK TEST - DECISION TREE ===\n\n";

// Test 1: Simple Manual Data
echo "[Test 1] Simple Classification\n";
$data = [
    ['Sunny', 'Hot', 'High', 'Weak'],
    ['Sunny', 'Hot', 'High', 'Strong'],
    ['Overcast', 'Hot', 'High', 'Weak'],
    ['Rain', 'Mild', 'High', 'Weak'],
    ['Rain', 'Cool', 'Normal', 'Weak'],
    ['Rain', 'Cool', 'Normal', 'Strong'],
    ['Overcast', 'Cool', 'Normal', 'Strong'],
    ['Sunny', 'Mild', 'High', 'Weak'],
    ['Sunny', 'Cool', 'Normal', 'Weak'],
    ['Rain', 'Mild', 'Normal', 'Weak']
];

$labels = ['No', 'No', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'No', 'Yes', 'Yes'];
$features = ['Outlook', 'Temperature', 'Humidity', 'Wind'];

$model = new DecisionTree(maxDepth: 5);
$model->fit($data, $labels, $features);

$testSample = ['Sunny', 'Cool', 'High', 'Strong'];
$prediction = $model->predict($testSample);
echo "Prediction for ['Sunny', 'Cool', 'High', 'Strong']: $prediction\n";

$accuracy = $model->calculateAccuracy($data, $labels);
echo "Training Accuracy: " . round($accuracy, 2) . "%\n\n";

// Test 2: Petshop Data (if CSV exists)
if (file_exists(__DIR__ . '/Dataset_Hewan_Petshop_Makassar_2024.csv')) {
    echo "[Test 2] Petshop Data Training\n";
    
    $preprocessor = new DataPreprocessor();
    $preprocessor->loadCSV(__DIR__ . '/Dataset_Hewan_Petshop_Makassar_2024.csv', true);
    
    echo "Loaded " . count($preprocessor->getData()) . " samples\n";
    
    // Preprocessing
    $preprocessor->categorizeWeight('Berat Badan (Kg)', 'general');
    $preprocessor->categorizeAge('Usia (Bulan)', 'bulan');
    $preprocessor->autoDiscretize('Frekuensi Kunjungan', 3, ['Jarang', 'Sedang', 'Sering']);
    
    $dataset = $preprocessor->splitFeaturesTarget('Kebutuhan Layanan Berikutnya');
    
    $model2 = new DecisionTree(maxDepth: 8);
    $model2->fit($dataset['features'], $dataset['target'], $dataset['feature_names']);
    
    $accuracy2 = $model2->calculateAccuracy($dataset['features'], $dataset['target']);
    echo "Petshop Model Accuracy: " . round($accuracy2, 2) . "%\n";
    
    // Feature importance
    $importance = $model2->getFeatureImportance($dataset['feature_names']);
    echo "\nTop 3 Important Features:\n";
    $count = 0;
    foreach ($importance as $feature => $score) {
        if ($count++ >= 3) break;
        echo "  - $feature: $score%\n";
    }
    
    // Save model
    $model2->saveModel(__DIR__ . '/test_model.json');
    echo "\nModel saved to test_model.json\n";
    
    // Test prediction
    $testPet = [
        'Kucing', 'Persia', 'Normal', 'Remaja', 'Sering', 
        'Grooming', 'Grooming', 'Whiskas Premium', 'Whiskas', '30'
    ];
    
    $result = $model2->predictWithConfidence($testPet);
    echo "\nTest Prediction:\n";
    echo "  Sample: Kucing Persia, 4kg, 18 bulan\n";
    echo "  Prediction: {$result['prediction']}\n";
    echo "  Confidence: {$result['confidence']}%\n";
    
} else {
    echo "[Test 2] SKIPPED - Dataset_Hewan_Petshop_Makassar_2024.csv not found\n";
}

echo "\n=== TEST COMPLETED ===\n";
