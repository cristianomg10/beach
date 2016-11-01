<?php
namespace App\ANN\Examples;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../vendor/autoload.php');


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

$pl = new PerceptronLearning(100, new StepFunction(), $expectedOutput);
$pl->setInput($input);
$pl->setLearningRate(0.5);
$pl->setMomentumRate(0.3);
$pl->setLoggable(new ScreenWriterLoggable());
$pl->train();

# verify
$pl->setInput($input);
$ret = $pl->run();

echo "Input test: {$input->transpose()}<br>";
echo "Respective output: {$ret->asColumnMatrix()} <br>";

echo "________________________________________________________________________________<br>";
echo "** OR LOGIC ** <br>";
# OR Logic
echo "Input test: {$input->transpose()}<br>";
echo "Respective output: {$ret->asColumnMatrix()} <br>";

$expectedOutput = new Vector([0, 1, 1, 1]);

$pl = new PerceptronLearning(100, new StepFunction(), $expectedOutput);
$pl->setInput($input);
$pl->setLearningRate(0.5);
$pl->setMomentumRate(0.3);
$pl->setLoggable(new ScreenWriterLoggable());
$pl->train();

# verify
$pl->setInput($input);
$ret = $pl->run();

echo "Input test: {$input->transpose()}<br>";
echo "Respective output: {$ret->asColumnMatrix()} <br>";
