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

$slp = new SingleLayerPerceptron(3, 100);
$slp->setInput($input);
$slp->setActivationFunction(new Step());
$slp->setExpectedOutput($expectedOutput);
$slp->setLearningRate(0.5);
$slp->setMomentumRate(0.3);
echo "Input ok<br>";