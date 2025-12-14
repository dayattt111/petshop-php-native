<?php
/**
 * Decision Tree Classifier (C4.5 Algorithm Implementation)
 * Pure PHP Native - No External Libraries
 * 
 * @author Xboxpetshop Development Team
 * @version 1.0
 */

class DecisionTree {
    
    private $tree = null;
    private $maxDepth;
    private $minSamplesLeaf;
    
    /**
     * Constructor
     * 
     * @param int $maxDepth Maximum depth of the tree (default: 10)
     * @param int $minSamplesLeaf Minimum samples required to be a leaf node (default: 1)
     */
    public function __construct($maxDepth = 10, $minSamplesLeaf = 1) {
        $this->maxDepth = $maxDepth;
        $this->minSamplesLeaf = $minSamplesLeaf;
    }
    
    /**
     * Train the decision tree
     * 
     * @param array $data Training data (array of arrays)
     * @param array $labels Target labels
     * @param array $featureNames Names of features
     * @return void
     */
    public function fit($data, $labels, $featureNames) {
        if (count($data) !== count($labels)) {
            throw new Exception("Data and labels must have the same length");
        }
        
        if (count($data) == 0) {
            throw new Exception("Training data cannot be empty");
        }
        
        $this->tree = $this->buildTree($data, $labels, $featureNames, 0);
    }
    
    /**
     * Build decision tree recursively
     * 
     * @param array $data Dataset
     * @param array $labels Labels
     * @param array $features Available features
     * @param int $depth Current depth
     * @return array Tree node
     */
    private function buildTree($data, $labels, $features, $depth) {
        $numSamples = count($labels);
        $numFeatures = count($features);
        
        // Stopping conditions
        if ($depth >= $this->maxDepth || 
            $numSamples < $this->minSamplesLeaf || 
            $this->isPure($labels) ||
            $numFeatures == 0) {
            return $this->createLeafNode($labels);
        }
        
        // Find best split
        $bestFeature = null;
        $bestGain = -INF;
        $bestSplits = null;
        
        foreach ($features as $featureIdx => $featureName) {
            $uniqueValues = $this->getUniqueValues($data, $featureIdx);
            
            if (count($uniqueValues) <= 1) {
                continue;
            }
            
            $gain = $this->calculateInformationGain($data, $labels, $featureIdx, $uniqueValues);
            
            if ($gain > $bestGain) {
                $bestGain = $gain;
                $bestFeature = $featureIdx;
                $bestSplits = $uniqueValues;
            }
        }
        
        // If no good split found, create leaf
        if ($bestFeature === null || $bestGain <= 0) {
            return $this->createLeafNode($labels);
        }
        
        // Create branches for each unique value
        $branches = [];
        foreach ($bestSplits as $value) {
            $subData = [];
            $subLabels = [];
            
            foreach ($data as $idx => $sample) {
                if ($sample[$bestFeature] == $value) {
                    $subData[] = $sample;
                    $subLabels[] = $labels[$idx];
                }
            }
            
            if (count($subData) > 0) {
                $branches[$value] = $this->buildTree($subData, $subLabels, $features, $depth + 1);
            }
        }
        
        return [
            'type' => 'node',
            'feature' => $bestFeature,
            'feature_name' => $features[$bestFeature],
            'branches' => $branches,
            'samples' => $numSamples
        ];
    }
    
    /**
     * Create a leaf node
     * 
     * @param array $labels Labels for the leaf
     * @return array Leaf node
     */
    private function createLeafNode($labels) {
        $classCounts = array_count_values($labels);
        arsort($classCounts);
        $predictedClass = array_key_first($classCounts);
        
        return [
            'type' => 'leaf',
            'class' => $predictedClass,
            'samples' => count($labels),
            'distribution' => $classCounts
        ];
    }
    
    /**
     * Check if labels are pure (all same class)
     * 
     * @param array $labels Labels to check
     * @return bool True if pure
     */
    private function isPure($labels) {
        return count(array_unique($labels)) === 1;
    }
    
    /**
     * Get unique values for a feature
     * 
     * @param array $data Dataset
     * @param int $featureIdx Feature index
     * @return array Unique values
     */
    private function getUniqueValues($data, $featureIdx) {
        $values = [];
        foreach ($data as $sample) {
            $values[] = $sample[$featureIdx];
        }
        return array_unique($values);
    }
    
    /**
     * Calculate entropy
     * 
     * @param array $labels Labels
     * @return float Entropy value
     */
    private function calculateEntropy($labels) {
        if (count($labels) == 0) {
            return 0;
        }
        
        $classCounts = array_count_values($labels);
        $entropy = 0.0;
        $total = count($labels);
        
        foreach ($classCounts as $count) {
            $probability = $count / $total;
            if ($probability > 0) {
                $entropy -= $probability * log($probability, 2);
            }
        }
        
        return $entropy;
    }
    
    /**
     * Calculate information gain for a feature
     * 
     * @param array $data Dataset
     * @param array $labels Labels
     * @param int $featureIdx Feature index
     * @param array $uniqueValues Unique values of the feature
     * @return float Information gain
     */
    private function calculateInformationGain($data, $labels, $featureIdx, $uniqueValues) {
        $totalSamples = count($labels);
        $parentEntropy = $this->calculateEntropy($labels);
        
        $weightedChildEntropy = 0.0;
        
        foreach ($uniqueValues as $value) {
            $subLabels = [];
            
            foreach ($data as $idx => $sample) {
                if ($sample[$featureIdx] == $value) {
                    $subLabels[] = $labels[$idx];
                }
            }
            
            if (count($subLabels) > 0) {
                $weight = count($subLabels) / $totalSamples;
                $weightedChildEntropy += $weight * $this->calculateEntropy($subLabels);
            }
        }
        
        return $parentEntropy - $weightedChildEntropy;
    }
    
    /**
     * Predict single sample
     * 
     * @param array $sample Sample data
     * @return string Predicted class
     */
    public function predict($sample) {
        if ($this->tree === null) {
            throw new Exception("Model not trained. Call fit() first.");
        }
        
        return $this->predictRecursive($sample, $this->tree);
    }
    
    /**
     * Recursive prediction
     * 
     * @param array $sample Sample data
     * @param array $node Current node
     * @return string Predicted class
     */
    private function predictRecursive($sample, $node) {
        if ($node['type'] === 'leaf') {
            return $node['class'];
        }
        
        $featureValue = $sample[$node['feature']];
        
        // If branch exists for this value, follow it
        if (isset($node['branches'][$featureValue])) {
            return $this->predictRecursive($sample, $node['branches'][$featureValue]);
        }
        
        // If value not seen during training, return most common class from available branches
        foreach ($node['branches'] as $branch) {
            if ($branch['type'] === 'leaf') {
                return $branch['class'];
            }
        }
        
        // Fallback: traverse to first leaf
        return $this->findFirstLeaf($node);
    }
    
    /**
     * Find first leaf in tree (fallback)
     * 
     * @param array $node Current node
     * @return string Class label
     */
    private function findFirstLeaf($node) {
        if ($node['type'] === 'leaf') {
            return $node['class'];
        }
        
        foreach ($node['branches'] as $branch) {
            return $this->findFirstLeaf($branch);
        }
        
        return 'Unknown';
    }
    
    /**
     * Predict multiple samples
     * 
     * @param array $samples Array of samples
     * @return array Array of predictions
     */
    public function predictBatch($samples) {
        $predictions = [];
        foreach ($samples as $sample) {
            $predictions[] = $this->predict($sample);
        }
        return $predictions;
    }
    
    /**
     * Get prediction with confidence
     * 
     * @param array $sample Sample data
     * @return array ['prediction' => class, 'confidence' => percentage, 'distribution' => array]
     */
    public function predictWithConfidence($sample) {
        if ($this->tree === null) {
            throw new Exception("Model not trained. Call fit() first.");
        }
        
        $node = $this->predictRecursiveWithNode($sample, $this->tree);
        
        if ($node['type'] !== 'leaf') {
            return [
                'prediction' => 'Unknown',
                'confidence' => 0,
                'distribution' => []
            ];
        }
        
        $total = array_sum($node['distribution']);
        $confidence = ($node['distribution'][$node['class']] / $total) * 100;
        
        // Calculate percentage distribution
        $distribution = [];
        foreach ($node['distribution'] as $class => $count) {
            $distribution[$class] = round(($count / $total) * 100, 2);
        }
        
        return [
            'prediction' => $node['class'],
            'confidence' => round($confidence, 2),
            'distribution' => $distribution,
            'samples' => $total
        ];
    }
    
    /**
     * Recursive prediction returning node
     * 
     * @param array $sample Sample data
     * @param array $node Current node
     * @return array Node (preferably leaf)
     */
    private function predictRecursiveWithNode($sample, $node) {
        if ($node['type'] === 'leaf') {
            return $node;
        }
        
        $featureValue = $sample[$node['feature']];
        
        if (isset($node['branches'][$featureValue])) {
            return $this->predictRecursiveWithNode($sample, $node['branches'][$featureValue]);
        }
        
        // Return first leaf as fallback
        foreach ($node['branches'] as $branch) {
            if ($branch['type'] === 'leaf') {
                return $branch;
            }
        }
        
        return $node;
    }
    
    /**
     * Export model to JSON
     * 
     * @return string JSON representation of the tree
     */
    public function exportModel() {
        if ($this->tree === null) {
            throw new Exception("Model not trained. Call fit() first.");
        }
        
        return json_encode([
            'tree' => $this->tree,
            'maxDepth' => $this->maxDepth,
            'minSamplesLeaf' => $this->minSamplesLeaf,
            'created_at' => date('Y-m-d H:i:s')
        ], JSON_PRETTY_PRINT);
    }
    
    /**
     * Import model from JSON
     * 
     * @param string $json JSON string
     * @return void
     */
    public function importModel($json) {
        $model = json_decode($json, true);
        
        if ($model === null || !isset($model['tree'])) {
            throw new Exception("Invalid model JSON");
        }
        
        $this->tree = $model['tree'];
        $this->maxDepth = $model['maxDepth'] ?? 10;
        $this->minSamplesLeaf = $model['minSamplesLeaf'] ?? 1;
    }
    
    /**
     * Save model to file
     * 
     * @param string $filename File path
     * @return bool Success
     */
    public function saveModel($filename) {
        $json = $this->exportModel();
        return file_put_contents($filename, $json) !== false;
    }
    
    /**
     * Load model from file
     * 
     * @param string $filename File path
     * @return bool Success
     */
    public function loadModel($filename) {
        if (!file_exists($filename)) {
            throw new Exception("Model file not found: " . $filename);
        }
        
        $json = file_get_contents($filename);
        $this->importModel($json);
        return true;
    }
    
    /**
     * Get tree structure (for debugging/visualization)
     * 
     * @return array Tree structure
     */
    public function getTree() {
        return $this->tree;
    }
    
    /**
     * Calculate model accuracy
     * 
     * @param array $data Test data
     * @param array $labels True labels
     * @return float Accuracy percentage
     */
    public function calculateAccuracy($data, $labels) {
        if (count($data) !== count($labels)) {
            throw new Exception("Data and labels must have the same length");
        }
        
        $correct = 0;
        foreach ($data as $idx => $sample) {
            $prediction = $this->predict($sample);
            if ($prediction == $labels[$idx]) {
                $correct++;
            }
        }
        
        return ($correct / count($labels)) * 100;
    }
    
    /**
     * Get feature importance (simplified version)
     * 
     * @param array $featureNames Feature names
     * @return array Feature importance scores
     */
    public function getFeatureImportance($featureNames) {
        if ($this->tree === null) {
            return [];
        }
        
        $importance = array_fill_keys($featureNames, 0);
        $this->calculateFeatureImportanceRecursive($this->tree, $importance);
        
        // Normalize
        $total = array_sum($importance);
        if ($total > 0) {
            foreach ($importance as $feature => $score) {
                $importance[$feature] = round(($score / $total) * 100, 2);
            }
        }
        
        arsort($importance);
        return $importance;
    }
    
    /**
     * Calculate feature importance recursively
     * 
     * @param array $node Current node
     * @param array &$importance Importance array (by reference)
     * @return void
     */
    private function calculateFeatureImportanceRecursive($node, &$importance) {
        if ($node['type'] === 'leaf') {
            return;
        }
        
        $importance[$node['feature_name']] += $node['samples'];
        
        foreach ($node['branches'] as $branch) {
            $this->calculateFeatureImportanceRecursive($branch, $importance);
        }
    }
}
