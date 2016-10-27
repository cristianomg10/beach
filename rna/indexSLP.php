<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;

require_once 'Perceptron.class.php';
require 'Step.class.php';
require 'SingleLayerPerceptron.php';

$input			= new Matrix([
    [0, 0, 1, 1],
    [0, 1, 0, 1]
]);

$expectedOutput = new Vector([0, 0, 0, 1]);

$slp = new SingleLayerPerceptron(3, 100, new Step(), $expectedOutput);
$slp->setInput($input);
$slp->setLearningRate(0.5);
$slp->setMomentumRate(0.3);
$slp->train();
echo "Input ok<br>";

# verify
$slp->setInput($input);
$ret = $slp->run();
echo "Input test: " . json_encode($input) . " | Output: " . $ret . "<br>";
