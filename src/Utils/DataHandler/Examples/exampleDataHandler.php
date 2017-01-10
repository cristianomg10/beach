<?php
namespace App\DataHandler\Examples;

use App\ActivationFunctions\SigmoidalFunction;
use App\ANN\ExtremeLearningMachine;
use App\DataHandler\CSVDataHandler;
use App\Utils\MatrixHelpers;
use MathPHP\LinearAlgebra\Matrix;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../../vendor/autoload.php');

$time_start = microtime(true);

$x = [0, 1, 2, 3];
$y = $x;
$x[0] = 4;

$data = new CSVDataHandler();
$data->open('../Datasets/iris.csv');
$data->setAttrIndex(4);
$data->setValidationRate(20);
$unlabeled = $data->getUnlabeledDataForTraining()->transpose();
$label = $data->getLabelForTraining();

$elm = new ExtremeLearningMachine(25, 30, new SigmoidalFunction());
$elm->setInput($unlabeled);
$elm->setExpectedOutput($label);
$elm->learn();

$test = $data->getUnlabeledDataForValidation();
$testValues = $data->getLabelForValidation();

echo "<br><br><b>Iris dataset</b><br>Should be:<br>$testValues<br> We've gotten:<br>";
$classification = $elm->classify($test);
echo $classification . "<br>";

$diff = array_diff_assoc($classification->getMatrix()[0], $testValues->getMatrix()[0]);

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "Correctly guessed: " . (count($classification->getMatrix()[0]) - count($diff)) . " out of " . count($classification->getMatrix()[0]);
echo "<br>Precision: " . (count($classification->getMatrix()[0]) - count($diff)) / count($classification->getMatrix()[0]) * 100 . "%";
echo "<br>Did it in $time seconds";
