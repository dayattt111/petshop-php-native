<?php
/**
 * Preprocessing Script - Add Target Column
 * Menambahkan kolom "Kebutuhan Layanan Berikutnya" berdasarkan pola
 */

require_once __DIR__ . '/DataPreprocessor.php';

echo "=== PREPROCESSING DATASET MAKASSAR 2024 ===\n\n";

// Load original CSV
$inputFile = __DIR__ . '/Dataset_Hewan_Petshop_Makassar_2024.csv';
$outputFile = __DIR__ . '/Dataset_Hewan_Petshop_Processed.csv';

$preprocessor = new DataPreprocessor();
$preprocessor->loadCSV($inputFile, true);

echo "[1/5] Data loaded: " . count($preprocessor->getData()) . " rows\n\n";

// Process data
echo "[2/5] Processing fields...\n";

$data = $preprocessor->getData();
$processedData = [];

foreach ($data as $row) {
    $newRow = [];
    
    // Skip No column
    $newRow[] = $row[1]; // Jenis Hewan
    $newRow[] = $row[2]; // Ras
    $newRow[] = $row[3]; // Berat Badan (Kg)
    
    // Convert Usia to bulan only
    $usiaStr = $row[4]; // e.g., "18 bulan" or "5 tahun"
    if (strpos($usiaStr, 'tahun') !== false) {
        $tahun = floatval($usiaStr);
        $usiaBulan = $tahun * 12;
    } else {
        $usiaBulan = floatval($usiaStr);
    }
    $newRow[] = $usiaBulan;
    
    // Convert Frekuensi to numeric
    $frekuensiStr = $row[5]; // e.g., "2x/bulan", "1x/3 bulan"
    if (strpos($frekuensiStr, '3 bulan') !== false) {
        $frekuensi = floatval($frekuensiStr) / 3;
    } else {
        $frekuensi = floatval($frekuensiStr);
    }
    $newRow[] = round($frekuensi, 1);
    
    // Extract main service category from "Jenis Layanan yang Sering Digunakan"
    $layananSering = $row[6];
    $mainCategory = extractMainCategory($layananSering);
    $newRow[] = $mainCategory;
    
    // Extract main service from "Layanan Terakhir yang Digunakan"
    $layananTerakhir = $row[7];
    $lastCategory = extractMainCategory($layananTerakhir);
    $newRow[] = $lastCategory;
    
    $newRow[] = $row[8]; // Jenis Pakan
    $newRow[] = $row[9]; // Merek Pakan
    
    // Convert date to days since
    $tanggal = $row[10]; // 2024-01-09
    try {
        $date = new DateTime($tanggal);
        $today = new DateTime('2024-12-15'); // Current date
        $diff = $today->diff($date);
        $daysSince = $diff->days;
    } catch (Exception $e) {
        $daysSince = 0;
    }
    $newRow[] = $daysSince;
    
    // Predict next service (LOGIC BASED)
    $nextService = predictNextService(
        $mainCategory,
        $lastCategory,
        $frekuensi,
        $daysSince,
        $usiaBulan
    );
    $newRow[] = $nextService;
    
    $processedData[] = $newRow;
}

echo "     Processed " . count($processedData) . " rows\n\n";

// Save processed data
echo "[3/5] Saving processed data...\n";

$headers = [
    'Jenis Hewan',
    'Ras',
    'Berat Badan (Kg)',
    'Usia (Bulan)',
    'Frekuensi Kunjungan',
    'Layanan Sering',
    'Layanan Terakhir',
    'Jenis Pakan',
    'Merek Pakan',
    'Hari Sejak Kunjungan',
    'Kebutuhan Layanan Berikutnya'
];

$handle = fopen($outputFile, 'w');
fputcsv($handle, $headers);
foreach ($processedData as $row) {
    fputcsv($handle, $row);
}
fclose($handle);

echo "     Saved to: $outputFile\n\n";

// Statistics
echo "[4/5] Dataset Statistics:\n";
$targetColumn = array_column($processedData, 10);
$targetDistribution = array_count_values($targetColumn);
arsort($targetDistribution);

foreach ($targetDistribution as $class => $count) {
    $percentage = round(($count / count($processedData)) * 100, 1);
    echo "     - $class: $count ($percentage%)\n";
}

echo "\n[5/5] Done! Ready for training.\n";

/**
 * Extract main category from service description
 */
function extractMainCategory($serviceStr) {
    $serviceStr = strtolower($serviceStr);
    
    if (strpos($serviceStr, 'grooming') !== false || 
        strpos($serviceStr, 'mandi') !== false ||
        strpos($serviceStr, 'potong kuku') !== false ||
        strpos($serviceStr, 'bersihkan') !== false ||
        strpos($serviceStr, 'perawatan bulu') !== false ||
        strpos($serviceStr, 'perawatan jamur') !== false) {
        return 'Grooming';
    }
    
    if (strpos($serviceStr, 'veterinary') !== false ||
        strpos($serviceStr, 'cek up') !== false ||
        strpos($serviceStr, 'sterilisasi') !== false ||
        strpos($serviceStr, 'pengobatan') !== false ||
        strpos($serviceStr, 'vaksinasi') !== false) {
        return 'Veterinary';
    }
    
    if (strpos($serviceStr, 'penitipan') !== false ||
        strpos($serviceStr, 'hotel') !== false) {
        return 'Pet Hotel';
    }
    
    return 'Konsultasi';
}

/**
 * Predict next service based on patterns
 */
function predictNextService($mainCategory, $lastCategory, $frekuensi, $daysSince, $usiaBulan) {
    // Logic 1: If last was Veterinary and days > 90, likely need checkup again
    if ($lastCategory === 'Veterinary' && $daysSince > 90) {
        return 'Veterinary';
    }
    
    // Logic 2: If frequent visitor (>2x/month) and main is Grooming
    if ($frekuensi >= 2 && $mainCategory === 'Grooming') {
        return 'Grooming';
    }
    
    // Logic 3: If Pet Hotel is common and days > 60
    if ($mainCategory === 'Pet Hotel' && $daysSince > 60) {
        return 'Pet Hotel';
    }
    
    // Logic 4: Young pets (<12 months) likely need Veterinary (vaccines)
    if ($usiaBulan < 12 && $daysSince > 30) {
        return 'Veterinary';
    }
    
    // Logic 5: If last was Grooming and days > 30
    if ($lastCategory === 'Grooming' && $daysSince > 30 && $frekuensi >= 1.5) {
        return 'Grooming';
    }
    
    // Logic 6: Default to main category if uncertain
    if ($daysSince > 45) {
        return $mainCategory;
    }
    
    // Default: Same as last category
    return $lastCategory;
}

echo "\n=== PREPROCESSING COMPLETED ===\n";
echo "Next step: php ml_prediction/train_model.php\n";
