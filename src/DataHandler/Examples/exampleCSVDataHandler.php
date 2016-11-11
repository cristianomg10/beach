<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/8/16
 * Time: 9:24 PM
 */

namespace App\DataHandler\Examples;

use App\ActivationFunctions\SigmoidalFunction;
use App\ANN\ExtremeLearningMachine;
use App\DataHandler\CSVDataHandler;
use MathPHP\LinearAlgebra\Matrix;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../vendor/autoload.php');

$data = new CSVDataHandler(0);
$data->open('../Datasets/wine-dataset.csv');
$data->setAttrIndex(0);
$unlabeled = $data->getUnlabeledDataForTraining()->transpose();
$label = $data->getLabelForTraining();

$elm = new ExtremeLearningMachine(250, 30, new SigmoidalFunction());
$elm->setInput($unlabeled);
$elm->setExpectedOutput($label);
$elm->learn();

$test = new Matrix([
    [12.37,1.17,1.92,19.6,78,2.11,2,0.27,1.04,4.68,1.12,3.48,510], // 2
    [13.4,4.6,2.86,25,112,1.98,0.96,0.27,1.11,8.5,0.67,1.92,630], //3
    [14.2,1.76,2.45,15.2,112,3.27,3.39,0.34,1.97,6.75,1.05,2.85,1450], // 1
    [13.05,3.86,2.32,22.5,85,1.65,1.59,0.61,1.62,4.8,0.84,2.01,515], // 2
    [13.72,1.43,2.5,16.7,108,3.4,3.67,0.19,2.04,6.8,0.89,2.87,1285], // 1
    [12.37,0.94,1.36,10.6,88,1.98,0.57,0.28,0.42,1.95,1.05,1.82,520], //2
    [12.33,1.1,2.28,16,101,2.05,1.09,0.63,0.41,3.27,1.25,1.67,680], // 2
    [12.64,1.36,2.02,16.8,100,2.02,1.41,0.53,0.62,5.75,0.98,1.59,450], // 2
    [12.04,4.3,2.38,22,80,2.1,1.75,0.42,1.35,2.6,0.79,2.57,580], // 2
    [12.86,1.35,2.32,18,122,1.51,1.25,0.21,0.94,4.1,0.76,1.29,630] // 3
]);

echo "<b>Wine dataset</b><br>Should be:<br>[2, 3, 1, 2, 1, 2, 2, 2, 2, 3]<br> We've gotten:<br>";
echo $elm->classify($test);

$data = new CSVDataHandler();
$data->open('../Datasets/iris.csv');
$data->setAttrIndex(4);
$unlabeled = $data->getUnlabeledDataForTraining()->transpose();
$label = $data->getLabelForTraining();

$elm = new ExtremeLearningMachine(120, 30, new SigmoidalFunction());
$elm->setInput($unlabeled);
$elm->setExpectedOutput($label);
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

echo "<br><br><b>Iris dataset</b><br>Should be:<br>[2, 3, 1, 2, 1, 2, 2, 2, 2, 3]<br> We've gotten:<br>";
echo $elm->classify($test);