<?php

namespace App\ML;

use PhpOption\None;

class RandomForestRegressor {
    
    private $featureSelect;
    private $criterionSelect;
    private $eval;
    
    public $criterion;
    public $nEstimator;
    public $minSampleSplit;
    public $maxDepth;
    public $maxFeature;
    public $randomGen;
    public $trees;
    public $X;
    public $y;
    
    public function __construct($criterion = 'mse', $nEstimator = 50, $minSampleSplit = 2, $maxDepth = 100, $maxFeature = 'sqrt', $randomState = 42, $trees = [], $X = null, $y = null) {
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
        
        $this->eval = [
            'mse' => function($yPred, $yTrue) {
                return $this->arrayMean($this->arrayPow($this->arraySubtract($yTrue, $yPred), 2));
            },
            'mae' => function($yPred, $yTrue) {
                return $this->arrayMean($this->arrayAbs($this->arraySubtract($yTrue, $yPred)));
            }
        ];
        
        $this->criterion = $criterion;
        $this->nEstimator = $nEstimator;
        $this->minSampleSplit = $minSampleSplit;
        $this->maxDepth = $maxDepth;
        $this->maxFeature = $maxFeature;
        $this->trees = $trees;
        $this->X = $X ? $this->toFloat32Array($X) : $X;
        $this->y = $y ? (is_array($y) ? $y : [$y]) : $y;
        
        mt_srand($randomState);
    }
    
    public function fit($X, $y) {
        $this->X = $this->toFloat32Array($X);
        $this->y = is_array($y) ? $y : [$y];
        
        for ($i = 0; $i < $this->nEstimator; $i++) {
            $randomState = mt_rand(1, $this->nEstimator + 1);
            $tree = new DecisionTree($this->criterion, $this->minSampleSplit, $this->maxDepth, $randomState, $this->maxFeature);
            $this->trees[] = $tree->fit($this->X, $this->y);
        }
        
        return $this;
    }
    
    public function predict($X) {
        $X = $this->toFloat32Array($X);
        $nOutputs = $this->isMultiDimensional($this->y) ? count($this->y[0]) : 1;
        $yPred = array_fill(0, $nOutputs, []);
        
        foreach ($this->trees as $tree) {
            $prediction = $tree->predict($X);
            
            if ($nOutputs > 1) {
                for ($i = 0; $i < $nOutputs; $i++) {
                    $column = array_column($prediction, $i);
                    $yPred[$i][] = $column;
                }
            } else {
                $yPred[0][] = $prediction;
            }
        }
        
        // Average predictions
        for ($i = 0; $i < $nOutputs; $i++) {
            $yPred[$i] = $this->arrayAverage($yPred[$i]);
        }
        
        // Transpose if multi-output
        if ($nOutputs > 1) {
            $result = [];
            for ($i = 0; $i < count($yPred[0]); $i++) {
                $row = [];
                for ($j = 0; $j < $nOutputs; $j++) {
                    $row[] = $yPred[$j][$i];
                }
                $result[] = $row;
            }
            return $result;
        }
        
        return $yPred[0];
    }
    
    public function export()
    {
        $trees = $this->trees;
        for($i = 0; $i < count($trees); $i++){
            $trees[$i] =  $trees[$i]->tree;
        }
        return [
            'criterion' => $this->criterion,
            'nEstimator' => $this->nEstimator,
            'minSampleSplit' => $this->minSampleSplit,
            'maxDepth' => $this->maxDepth,
            'maxFeature' => $this->maxFeature,
            'trees' => $trees,
            'X' => $this->X,
            'y' => $this->y,
        ];
    }

    public function import($data)
    {
        
        $this->criterion = $data['criterion'];
        $this->nEstimator = $data['nEstimator'];
        $this->minSampleSplit = $data['minSampleSplit'];
        $this->maxDepth = $data['maxDepth'];
        $this->maxFeature = $data['maxFeature'];
        $this->X = $data['X'];
        $this->y = $data['y'];
        $trees = [];
        foreach ($data['trees'] as $tree){
            $dt = new DecisionTree($this->criterion, $this->minSampleSplit, $this->maxDepth,  maxFeatures: $this->maxFeature, tree:$tree);
            $dt->multiOutput = count($data['y'][0]) > 1 && (isset($data['y'][0]) && is_array($data['y'][0]));
            $trees[] = $dt;
        }
        $this->trees = $trees;

        return $this;
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
    
    private function arrayAverage($arrays) {
        $result = [];
        $count = count($arrays);
        
        for ($i = 0; $i < count($arrays[0]); $i++) {
            $sum = 0;
            foreach ($arrays as $array) {
                $sum += $array[$i];
            }
            $result[] = $sum / $count;
        }
        
        return $result;
    }
}