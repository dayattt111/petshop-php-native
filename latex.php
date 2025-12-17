<?php 

if ($depth >= $this->maxDepth || 
    $numSamples < $this->minSamplesLeaf || 
    $this->isPure($labels) ||
    $numFeatures == 0) {
    return $this->createLeafNode($labels);
}
?>