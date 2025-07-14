<?php

namespace App\ML;

use Exception;
use InvalidArgumentException;

class DecisionTree {
    
    private $eval;
    private $featureSelect;
    private $criterionSelect;
    
    public $criterion;
    public $minSampleSplit;
    public $maxDepth;
    public $maxFeature;
    public $randomState;
    public $tree;
    public $X;
    public $y;
    public $multiOutput;
    
    public function __construct($criterion = 'mse', $minSampleSplit = 2, $maxDepth = 100, $randomState = null, $maxFeatures = null, $tree = []) {
        if (is_float($maxFeatures)) {
            throw new InvalidArgumentException("max_feature params cannot accept float");
        }
        
        $this->eval = [
            'mse' => function($yPred, $yTrue) {
                return $this->arrayMean($this->arrayPow($this->arraySubtract($yTrue, $yPred), 2));
            },
            'mae' => function($yPred, $yTrue) {
                return $this->arrayMean($this->arrayAbs($this->arraySubtract($yTrue, $yPred)));
            }
        ];
        
        $this->featureSelect = [
            null => function($x) { return $x; },
            'log2' => function($x) { return log($x, 2); },
            'sqrt' => function($x) { return sqrt($x); }
        ];
        
        $this->criterionSelect = [
            'mse' => function($y) {
                $mean = $this->arrayMean($y);
                return $this->arrayMean($this->arrayPow($this->arraySubtract($y, $mean), 2));
            },
            'mae' => function($y) {
                $median = $this->arrayMedian($y);
                return $this->arrayMean($this->arrayAbs($this->arraySubtract($y, $median)));
            }
        ];
        
        $this->criterion = $criterion;
        $this->minSampleSplit = $minSampleSplit;
        $this->maxDepth = $maxDepth;
        $this->maxFeature = $maxFeatures;
        $this->randomState = $randomState;
        $this->tree = $tree;
        
        if ($randomState !== null) {
            mt_srand($randomState);
        }
    }
    
    public function fit($X, $y) {
        $this->X = $this->toFloat32Array($X);
        $this->y = is_array($y) ? $y : [$y];
        
        // Check if multi-output
        $this->multiOutput = $this->isMultiDimensional($this->y) && count($this->y[0]) > 1;
        
        if ($this->multiOutput) {
            $nOutput = count($this->y[0]);
            $this->tree = [];
            for ($i = 0; $i < $nOutput; $i++) {
                $yColumn = array_column($this->y, $i);
                $this->tree[] = $this->buildTree($this->X, $yColumn);
            }
        } else {
            $this->tree = $this->buildTree($this->X, $this->y);
        }
        
        return $this;
    }
    
    private function buildTree($X, $y, $depth = 0) {
        $n = count($X);
        $m = count($X[0]);
        $nLabel = count(array_unique($y));
        
        if ($nLabel == 1 || $this->arrayVar($y) < 1e-10 || $n <= $this->minSampleSplit || $depth >= $this->maxDepth) {
            return ['leaf' => true, 'value' => $this->arrayMean($y)];
        }
        
        $maxFeat = intval($this->featureSelect[$this->maxFeature]($m));
        
        try {
            $featIdxs = $this->randomChoice($m, $maxFeat);
        } catch (Exception $e) {
            throw new InvalidArgumentException("Unknown max feature value got " . $this->maxFeature);
        }
        
        list($gain, $feat, $thresh) = $this->bestSplit($X, $y, $featIdxs);
        
        if ($feat === null || $thresh === null) {
            return ['leaf' => true, 'value' => $this->arrayMean($y)];
        }
        
        list($leftIdx, $rightIdx) = $this->split($this->getColumn($X, $feat), $thresh);
        
        if (count($leftIdx) == 0 || count($rightIdx) == 0) {
            return ['leaf' => true, 'value' => $this->arrayMean($y)];
        }
        
        $leftX = $this->getRows($X, $leftIdx);
        $rightX = $this->getRows($X, $rightIdx);
        $leftY = $this->getElements($y, $leftIdx);
        $rightY = $this->getElements($y, $rightIdx);
        
        return [
            'leaf' => false,
            'feature' => $feat,
            'threshold' => $thresh,
            'left' => $this->buildTree($leftX, $leftY, $depth + 1),
            'right' => $this->buildTree($rightX, $rightY, $depth + 1),
            'ig' => $gain
        ];
    }
    
    private function bestSplit($X, $y, $featIdxs) {
        $bestGain = -1;
        $bestFeat = null;
        $bestThresh = null;
        
        foreach ($featIdxs as $featIdx) {
            $XCol = $this->getColumn($X, $featIdx);
            $thresholds = array_unique($XCol);
            
            foreach ($thresholds as $threshold) {
                $ig = $this->impurityDecrease($XCol, $y, $threshold);
                if ($ig > $bestGain) {
                    $bestGain = $ig;
                    $bestFeat = $featIdx;
                    $bestThresh = $threshold;
                }
            }
        }
        
        return [$bestGain, $bestFeat, $bestThresh];
    }
    
    private function criterion($y) {
        if (!isset($this->criterionSelect[$this->criterion])) {
            throw new InvalidArgumentException("Unknown or Unsupported criterion got " . $this->criterion);
        }
        return $this->criterionSelect[$this->criterion]($y);
    }
    
    private function split($X, $threshold) {
        $left = [];
        $right = [];
        
        foreach ($X as $i => $val) {
            if ($val <= $threshold) {
                $left[] = $i;
            } else {
                $right[] = $i;
            }
        }
        
        return [$left, $right];
    }
    
    private function impurityDecrease($X, $y, $threshold) {
        $parent = $this->criterion($y);
        list($leftIdxs, $rightIdxs) = $this->split($X, $threshold);
        
        $n = count($y);
        $nLeft = count($leftIdxs);
        $nRight = count($rightIdxs);
        
        if ($nRight == 0 || $nLeft == 0) {
            return -1;
        }
        
        $leftY = $this->getElements($y, $leftIdxs);
        $rightY = $this->getElements($y, $rightIdxs);
        
        $childLeft = $this->criterion($leftY);
        $childRight = $this->criterion($rightY);
        
        $children = ($nLeft / $n) * $childLeft + ($nRight / $n) * $childRight;
        
        return $parent - $children;
    }
    
    public function predict($X) {
        $X = $this->toFloat32Array($X);
        $result = [];
        
        if ($this->multiOutput) {
            for ($treeI = 0; $treeI < count($this->tree); $treeI++) {
                $predictions = [];
                foreach ($X as $x) {
                    $predictions[] = $this->traverse($x, $this->tree[$treeI]);
                }
                $result[] = $predictions;
            }
            // Transpose result
            $transposed = [];
            for ($i = 0; $i < count($result[0]); $i++) {
                $row = [];
                for ($j = 0; $j < count($result); $j++) {
                    $row[] = $result[$j][$i];
                }
                $transposed[] = $row;
            }
            return $transposed;
        } else {
            foreach ($X as $x) {
                $result[] = $this->traverse($x, $this->tree);
            }
        }
        
        return $result;
    }
    
    private function traverse($x, $node) {
        if ($node['leaf']) {
            return $node['value'];
        }
        
        if ($x[$node['feature']] > $node['threshold']) {
            return $this->traverse($x, $node['right']);
        }
        return $this->traverse($x, $node['left']);
    }
    
    public function score($criterion = 'mse') {
        $yPred = $this->predict($this->X);
        
        if (isset($this->eval[$criterion])) {
            $error = $this->eval[$criterion]($yPred, $this->y);
        } else {
            throw new InvalidArgumentException("Unknown or Unsupported criterion got " . $criterion);
        }
        return $error;
    }
    
    // Utility functions
    private function toFloat32Array($arr) {
        return array_map(function($row) {
            return array_map('floatval', $row);
        }, $arr);
    }
    
    private function isMultiDimensional($arr) {
        return isset($arr[0]) && is_array($arr[0]);
    }
    
    private function arrayMean($arr) {
        return array_sum($arr) / count($arr);
    }
    
    private function arrayMedian($arr) {
        sort($arr);
        $count = count($arr);
        $middle = floor(($count - 1) / 2);
        
        if ($count % 2) {
            return $arr[$middle];
        } else {
            return ($arr[$middle] + $arr[$middle + 1]) / 2;
        }
    }
    
    private function arrayVar($arr) {
        $mean = $this->arrayMean($arr);
        $sum = 0;
        foreach ($arr as $val) {
            $sum += pow($val - $mean, 2);
        }
        return $sum / count($arr);
    }
    
    private function arraySubtract($arr1, $arr2) {
        if (is_array($arr2)) {
            return array_map(function($a, $b) { return $a - $b; }, $arr1, $arr2);
        } else {
            return array_map(function($a) use ($arr2) { return $a - $arr2; }, $arr1);
        }
    }
    
    private function arrayPow($arr, $power) {
        return array_map(function($val) use ($power) { return pow($val, $power); }, $arr);
    }
    
    private function arrayAbs($arr) {
        return array_map('abs', $arr);
    }
    
    private function getColumn($matrix, $col) {
        return array_column($matrix, $col);
    }
    
    private function getRows($matrix, $indices) {
        $result = [];
        foreach ($indices as $idx) {
            $result[] = $matrix[$idx];
        }
        return $result;
    }
    
    private function getElements($arr, $indices) {
        $result = [];
        foreach ($indices as $idx) {
            $result[] = $arr[$idx];
        }
        return $result;
    }
    
    private function randomChoice($max, $count) {
        if ($count > $max) {
            throw new InvalidArgumentException("Cannot choose $count elements from $max");
        }
        
        $indices = range(0, $max - 1);
        shuffle($indices);
        return array_slice($indices, 0, $count);
    }
}