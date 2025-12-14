<?php
/**
 * Data Preprocessor for Decision Tree
 * Handles CSV/Excel conversion, data cleaning, and feature engineering
 * 
 * @author Xboxpetshop Development Team
 * @version 1.0
 */

class DataPreprocessor {
    
    private $data = [];
    private $headers = [];
    private $encoders = [];
    
    /**
     * Load data from CSV file
     * 
     * @param string $filename CSV file path
     * @param bool $hasHeader Whether first row is header
     * @return bool Success
     */
    public function loadCSV($filename, $hasHeader = true) {
        if (!file_exists($filename)) {
            throw new Exception("CSV file not found: " . $filename);
        }
        
        $handle = fopen($filename, 'r');
        if ($handle === false) {
            throw new Exception("Cannot open CSV file: " . $filename);
        }
        
        $this->data = [];
        $this->headers = [];
        $rowIndex = 0;
        
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if ($rowIndex === 0 && $hasHeader) {
                $this->headers = $row;
            } else {
                $this->data[] = $row;
            }
            $rowIndex++;
        }
        
        fclose($handle);
        
        if (!$hasHeader) {
            $this->headers = array_keys($this->data[0]);
        }
        
        return true;
    }
    
    /**
     * Save data to CSV file
     * 
     * @param string $filename CSV file path
     * @param bool $includeHeader Include header row
     * @return bool Success
     */
    public function saveCSV($filename, $includeHeader = true) {
        $handle = fopen($filename, 'w');
        if ($handle === false) {
            throw new Exception("Cannot write CSV file: " . $filename);
        }
        
        if ($includeHeader && count($this->headers) > 0) {
            fputcsv($handle, $this->headers);
        }
        
        foreach ($this->data as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);
        return true;
    }
    
    /**
     * Get column by name or index
     * 
     * @param mixed $column Column name or index
     * @return array Column data
     */
    public function getColumn($column) {
        $columnIndex = is_numeric($column) ? $column : array_search($column, $this->headers);
        
        if ($columnIndex === false) {
            throw new Exception("Column not found: " . $column);
        }
        
        $columnData = [];
        foreach ($this->data as $row) {
            if (isset($row[$columnIndex])) {
                $columnData[] = $row[$columnIndex];
            }
        }
        
        return $columnData;
    }
    
    /**
     * Discretize (bin) numeric column
     * Useful for Berat Badan and Usia
     * 
     * @param mixed $column Column name or index
     * @param array $bins Array of bin edges [min, edge1, edge2, ..., max]
     * @param array $labels Labels for each bin
     * @return void
     */
    public function discretizeColumn($column, $bins, $labels) {
        $columnIndex = is_numeric($column) ? $column : array_search($column, $this->headers);
        
        if ($columnIndex === false) {
            throw new Exception("Column not found: " . $column);
        }
        
        if (count($labels) !== count($bins) - 1) {
            throw new Exception("Number of labels must be bins - 1");
        }
        
        foreach ($this->data as &$row) {
            if (!isset($row[$columnIndex])) {
                continue;
            }
            
            $value = floatval($row[$columnIndex]);
            
            for ($i = 0; $i < count($bins) - 1; $i++) {
                if ($value >= $bins[$i] && $value < $bins[$i + 1]) {
                    $row[$columnIndex] = $labels[$i];
                    break;
                } elseif ($i === count($bins) - 2 && $value >= $bins[$i + 1]) {
                    // Handle edge case for last bin (inclusive upper bound)
                    $row[$columnIndex] = $labels[$i];
                    break;
                }
            }
        }
    }
    
    /**
     * Auto-discretize numeric column using equal-width binning
     * 
     * @param mixed $column Column name or index
     * @param int $numBins Number of bins (default: 4)
     * @param array $customLabels Custom labels (optional)
     * @return array Bin information
     */
    public function autoDiscretize($column, $numBins = 4, $customLabels = null) {
        $columnIndex = is_numeric($column) ? $column : array_search($column, $this->headers);
        
        if ($columnIndex === false) {
            throw new Exception("Column not found: " . $column);
        }
        
        // Get numeric values
        $values = [];
        foreach ($this->data as $row) {
            if (isset($row[$columnIndex]) && is_numeric($row[$columnIndex])) {
                $values[] = floatval($row[$columnIndex]);
            }
        }
        
        if (count($values) == 0) {
            throw new Exception("No numeric values found in column");
        }
        
        $min = min($values);
        $max = max($values);
        $range = $max - $min;
        
        if ($range == 0) {
            // All values are the same
            $bins = [$min, $min + 1];
            $labels = ['Constant'];
        } else {
            $binWidth = $range / $numBins;
            $bins = [];
            for ($i = 0; $i <= $numBins; $i++) {
                $bins[] = $min + ($i * $binWidth);
            }
            
            if ($customLabels !== null && count($customLabels) === $numBins) {
                $labels = $customLabels;
            } else {
                $labels = [];
                for ($i = 0; $i < $numBins; $i++) {
                    $labels[] = 'Bin_' . ($i + 1);
                }
            }
        }
        
        $this->discretizeColumn($columnIndex, $bins, $labels);
        
        return [
            'bins' => $bins,
            'labels' => $labels,
            'min' => $min,
            'max' => $max
        ];
    }
    
    /**
     * Clean missing values
     * 
     * @param string $strategy Strategy: 'remove', 'fill_mode', 'fill_value'
     * @param mixed $fillValue Value to fill (if strategy is 'fill_value')
     * @return int Number of rows affected
     */
    public function handleMissingValues($strategy = 'remove', $fillValue = null) {
        $affected = 0;
        
        if ($strategy === 'remove') {
            $cleanData = [];
            foreach ($this->data as $row) {
                $hasMissing = false;
                foreach ($row as $value) {
                    if ($value === '' || $value === null || $value === 'NULL') {
                        $hasMissing = true;
                        $affected++;
                        break;
                    }
                }
                if (!$hasMissing) {
                    $cleanData[] = $row;
                }
            }
            $this->data = $cleanData;
        } elseif ($strategy === 'fill_mode') {
            // Fill with most common value per column
            $columnModes = [];
            foreach ($this->headers as $colIdx => $header) {
                $columnValues = $this->getColumn($colIdx);
                $columnValues = array_filter($columnValues, function($v) {
                    return $v !== '' && $v !== null && $v !== 'NULL';
                });
                
                if (count($columnValues) > 0) {
                    $valueCounts = array_count_values($columnValues);
                    arsort($valueCounts);
                    $columnModes[$colIdx] = array_key_first($valueCounts);
                } else {
                    $columnModes[$colIdx] = 'Unknown';
                }
            }
            
            foreach ($this->data as &$row) {
                foreach ($row as $colIdx => &$value) {
                    if ($value === '' || $value === null || $value === 'NULL') {
                        $value = $columnModes[$colIdx];
                        $affected++;
                    }
                }
            }
        } elseif ($strategy === 'fill_value') {
            foreach ($this->data as &$row) {
                foreach ($row as &$value) {
                    if ($value === '' || $value === null || $value === 'NULL') {
                        $value = $fillValue;
                        $affected++;
                    }
                }
            }
        }
        
        return $affected;
    }
    
    /**
     * Normalize text values (trim, lowercase, etc)
     * 
     * @param mixed $column Column name or index (null for all columns)
     * @return void
     */
    public function normalizeText($column = null) {
        if ($column === null) {
            // Normalize all columns
            foreach ($this->data as &$row) {
                foreach ($row as &$value) {
                    if (is_string($value)) {
                        $value = trim($value);
                        $value = ucwords(strtolower($value));
                    }
                }
            }
        } else {
            $columnIndex = is_numeric($column) ? $column : array_search($column, $this->headers);
            
            if ($columnIndex === false) {
                throw new Exception("Column not found: " . $column);
            }
            
            foreach ($this->data as &$row) {
                if (isset($row[$columnIndex]) && is_string($row[$columnIndex])) {
                    $row[$columnIndex] = trim($row[$columnIndex]);
                    $row[$columnIndex] = ucwords(strtolower($row[$columnIndex]));
                }
            }
        }
    }
    
    /**
     * Split data into features and target
     * 
     * @param mixed $targetColumn Target column name or index
     * @return array ['features' => [], 'target' => [], 'feature_names' => []]
     */
    public function splitFeaturesTarget($targetColumn) {
        $targetIndex = is_numeric($targetColumn) ? $targetColumn : array_search($targetColumn, $this->headers);
        
        if ($targetIndex === false) {
            throw new Exception("Target column not found: " . $targetColumn);
        }
        
        $features = [];
        $target = [];
        $featureNames = [];
        
        // Get feature names (exclude target)
        foreach ($this->headers as $idx => $header) {
            if ($idx !== $targetIndex) {
                $featureNames[] = $header;
            }
        }
        
        // Split data
        foreach ($this->data as $row) {
            $featureRow = [];
            foreach ($row as $idx => $value) {
                if ($idx === $targetIndex) {
                    $target[] = $value;
                } else {
                    $featureRow[] = $value;
                }
            }
            $features[] = $featureRow;
        }
        
        return [
            'features' => $features,
            'target' => $target,
            'feature_names' => $featureNames
        ];
    }
    
    /**
     * Train-test split
     * 
     * @param float $testSize Test size ratio (0.0 - 1.0)
     * @param bool $shuffle Shuffle data before split
     * @return array ['train' => [], 'test' => []]
     */
    public function trainTestSplit($testSize = 0.2, $shuffle = true) {
        $data = $this->data;
        
        if ($shuffle) {
            shuffle($data);
        }
        
        $totalRows = count($data);
        $testRows = (int)($totalRows * $testSize);
        $trainRows = $totalRows - $testRows;
        
        $train = array_slice($data, 0, $trainRows);
        $test = array_slice($data, $trainRows);
        
        return [
            'train' => $train,
            'test' => $test
        ];
    }
    
    /**
     * Get data summary
     * 
     * @return array Summary statistics
     */
    public function getSummary() {
        $summary = [
            'total_rows' => count($this->data),
            'total_columns' => count($this->headers),
            'columns' => []
        ];
        
        foreach ($this->headers as $idx => $header) {
            $columnData = $this->getColumn($idx);
            $uniqueValues = array_unique($columnData);
            
            $summary['columns'][$header] = [
                'index' => $idx,
                'unique_values' => count($uniqueValues),
                'sample_values' => array_slice($uniqueValues, 0, 5),
                'missing_count' => count(array_filter($columnData, function($v) {
                    return $v === '' || $v === null || $v === 'NULL';
                }))
            ];
        }
        
        return $summary;
    }
    
    /**
     * Get headers
     * 
     * @return array Headers
     */
    public function getHeaders() {
        return $this->headers;
    }
    
    /**
     * Get data
     * 
     * @return array Data
     */
    public function getData() {
        return $this->data;
    }
    
    /**
     * Set data
     * 
     * @param array $data Data to set
     * @return void
     */
    public function setData($data) {
        $this->data = $data;
    }
    
    /**
     * Calculate days since date
     * Useful for "Tanggal Kunjungan Terakhir"
     * 
     * @param mixed $column Column name or index
     * @param string $format Date format (default: Y-m-d)
     * @return void
     */
    public function convertDateToDaysSince($column, $format = 'Y-m-d') {
        $columnIndex = is_numeric($column) ? $column : array_search($column, $this->headers);
        
        if ($columnIndex === false) {
            throw new Exception("Column not found: " . $column);
        }
        
        $today = new DateTime();
        
        foreach ($this->data as &$row) {
            if (isset($row[$columnIndex])) {
                try {
                    $date = DateTime::createFromFormat($format, $row[$columnIndex]);
                    if ($date) {
                        $diff = $today->diff($date);
                        $row[$columnIndex] = $diff->days;
                    }
                } catch (Exception $e) {
                    $row[$columnIndex] = 0;
                }
            }
        }
    }
    
    /**
     * Create age categories from numeric age
     * 
     * @param mixed $column Column name or index
     * @param string $unit Unit: 'bulan' or 'tahun'
     * @return array Category information
     */
    public function categorizeAge($column, $unit = 'bulan') {
        if ($unit === 'bulan') {
            $bins = [0, 6, 12, 24, 60, 120, PHP_INT_MAX];
            $labels = ['Bayi', 'Muda', 'Remaja', 'Dewasa', 'Tua', 'Sangat Tua'];
        } else { // tahun
            $bins = [0, 1, 2, 5, 10, 15, PHP_INT_MAX];
            $labels = ['Bayi', 'Muda', 'Remaja', 'Dewasa', 'Tua', 'Sangat Tua'];
        }
        
        $this->discretizeColumn($column, $bins, $labels);
        
        return [
            'bins' => $bins,
            'labels' => $labels,
            'unit' => $unit
        ];
    }
    
    /**
     * Create weight categories
     * 
     * @param mixed $column Column name or index
     * @param string $animalType Animal type for specific categorization
     * @return array Category information
     */
    public function categorizeWeight($column, $animalType = 'general') {
        if ($animalType === 'kucing' || $animalType === 'cat') {
            $bins = [0, 3, 5, 7, PHP_INT_MAX];
            $labels = ['Kecil', 'Normal', 'Besar', 'Sangat Besar'];
        } elseif ($animalType === 'anjing' || $animalType === 'dog') {
            $bins = [0, 5, 15, 30, 50, PHP_INT_MAX];
            $labels = ['Sangat Kecil', 'Kecil', 'Sedang', 'Besar', 'Sangat Besar'];
        } else {
            $bins = [0, 5, 10, 20, PHP_INT_MAX];
            $labels = ['Kecil', 'Sedang', 'Besar', 'Sangat Besar'];
        }
        
        $this->discretizeColumn($column, $bins, $labels);
        
        return [
            'bins' => $bins,
            'labels' => $labels,
            'animal_type' => $animalType
        ];
    }
}
