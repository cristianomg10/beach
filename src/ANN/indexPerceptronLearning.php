<?php
namespace App\ANN;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../vendor/autoload.php');


use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;

use App\ANN\Perceptron;
use App\ANN\PerceptronLearning;
use App\ActivationFunctions\StepFunction;
use App\Loggable\ScreenWriterLoggable;

$input			= new Matrix([
    [0, 0, 1, 1],
    [0, 1, 0, 1]
]);

# AND Logic

echo "________________________________________________________________________________<br>";
echo "** AND LOGIC ** <br>";
$expectedOutput = new Vector([0, 0, 0, 1]);

$slp = new PerceptronLearning(100, new StepFunction(), $expectedOutput);
$slp->setInput($input);
$slp->setLearningRate(0.5);
$slp->setMomentumRate(0.3);
$slp->setLoggable(new ScreenWriterLoggable());
$slp->train();

# verify
$slp->setInput($input);
$ret = $slp->run();

echo "Input test: {$input->transpose()}<br>";
echo "Respective output: {$ret->asColumnMatrix()} <br>";

echo "________________________________________________________________________________<br>";
echo "** OR LOGIC ** <br>";
# OR Logic
echo "Input test: {$input->transpose()}<br>";
echo "Respective output: {$ret->asColumnMatrix()} <br>";

$expectedOutput = new Vector([0, 1, 1, 1]);

$slp = new PerceptronLearning(100, new StepFunction(), $expectedOutput);
$slp->setInput($input);
$slp->setLearningRate(0.5);
$slp->setMomentumRate(0.3);
$slp->setLoggable(new ScreenWriterLoggable());
$slp->train();

# verify
$slp->setInput($input);
$ret = $slp->run();

echo "Input test: {$input->transpose()}<br>";
echo "Respective output: {$ret->asColumnMatrix()} <br>";
