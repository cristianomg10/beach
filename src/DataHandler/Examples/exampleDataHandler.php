<?php
namespace App\DataHandler\Examples;

use App\ActivationFunctions\SigmoidalFunction;
use App\ANN\ExtremeLearningMachine;
use App\DataHandler\CSVDataHandler;
use MathPHP\LinearAlgebra\Matrix;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../vendor/autoload.php');

$data = new CSVDataHandler();
$data->open('../Datasets/iris.csv');
$data->setAttrIndex(4);
$data->setValidationRate(20);
$unlabeled = $data->getUnlabeledDataForTraining()->transpose();
$label = $data->getLabelForTraining();

$elm = new ExtremeLearningMachine(120, 30, new SigmoidalFunction());
$elm->setInput($unlabeled);
$elm->setOutput($label);
$elm->learn();

$test = new Matrix([
    [6.0,2.2,4.0,1.0], // 2
    [6.0,2.2,5.0,1.5], //3
    [4.8,3.4,1.9,0.2], // 1
    [6.1,2.8,4.7,1.2], // 2
    [4.4,3.0,1.3,0.2], // 1
    [6.0,2.7,5.1,1.6], //2
    [6.3,2.3,4.4,1.3], // 2
    [6.1,3.0,4.6,1.4], // 2
    [6.1,2.8,4.0,1.3], // 2
    [7.2,3.0,5.8,1.6] // 3
]);

$test = $data->getUnlabeledDataForValidation();
$testValues = $data->getLabelForValidation();

echo "<br><br><b>Iris dataset</b><br>Should be:<br>$testValues<br> We've gotten:<br>";
$classification = $elm->classify($test);
echo $classification . "<br>";

$diff = array_diff_assoc($classification->getMatrix()[0], $testValues->getMatrix()[0]);

echo "Correctly guessed: " . (count($classification->getMatrix()[0]) - count($diff)) . " out of " . count($classification->getMatrix()[0]);
//echo new Matrix([array_diff($classification->getMatrix(), $testValues->getMatrix())]);