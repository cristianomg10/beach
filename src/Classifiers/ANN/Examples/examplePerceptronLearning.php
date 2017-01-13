<?php
namespace App\Classifiers\ANN\Examples;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../../vendor/autoload.php');


use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;
use App\Classifiers\ANN\Perceptron;
use App\Classifiers\ANN\PerceptronLearning;
use App\Utils\Functions\ActivationFunctions\StepFunction;
use App\Utils\Loggable\TerminalLoggable;

$input			= new Matrix([
    [0, 0, 1, 1],
    [0, 1, 0, 1]
]);

# AND Logic

echo "________________________________________________________________________________\n";
echo "** AND LOGIC ** \n";
$expectedOutput = new Matrix([[0, 0, 0, 1]]);

$pl = new PerceptronLearning(100, new StepFunction(), $expectedOutput);
$pl->setInput($input);
$pl->setLearningRate(0.5);
$pl->setMomentumRate(0.3);
$pl->setLoggable(new TerminalLoggable());
$pl->learn();

# verify
$pl->setInput($input);
$ret = $pl->run();

echo "Input test: {$input->transpose()}\n";
echo "Respective output: {$ret->asColumnMatrix()} \n";

echo "________________________________________________________________________________\n";
echo "** OR LOGIC ** \n";
# OR Logic
echo "Input test: \n{$input->transpose()}\n";
echo "Respective output: \n{$ret->asColumnMatrix()} \n";

$expectedOutput = new Matrix([[0, 1, 1, 1]]);

$pl = new PerceptronLearning(100, new StepFunction(), $expectedOutput);
$pl->setInput($input);
$pl->setLearningRate(0.5);
$pl->setMomentumRate(0.3);
$pl->setLoggable(new TerminalLoggable());
$pl->learn();

# verify
$pl->setInput($input);
$ret = $pl->run();

echo "Input test: \n{$input->transpose()}\n";
echo "Respective output: \n{$ret->asColumnMatrix()} \n";
