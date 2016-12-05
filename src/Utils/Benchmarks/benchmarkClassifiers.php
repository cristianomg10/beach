<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/2/16
 * Time: 5:52 PM
 */

namespace App\Utils\Benchmarks;

use App\ANN\ExtremeLearningMachine;
use App\ANN\Perceptron;
use App\ANN\PerceptronLearning;
use App\LeastSquares\DeterministicLeastSquares;
use App\Utils\DataHandler\CSVDataHandler;
use App\Utils\Functions\ActivationFunctions\RoundedSigmoidalFunction;
use App\Utils\Functions\ActivationFunctions\RoundFunction;
use App\Utils\Functions\ActivationFunctions\SigmoidalFunction;
use App\Utils\Functions\ActivationFunctions\StepFunction;
use App\Utils\Validations\HoldoutValidation;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

$elm = new ExtremeLearningMachine(30, 3, new SigmoidalFunction());
$pcptron = new PerceptronLearning(100, new RoundFunction());
$dls = new DeterministicLeastSquares();

$data = new CSVDataHandler(0);
$data->open('../DataHandler/Datasets/iris.csv');

echo "\n\nExtreme Learning Machine\n";
$ho = new HoldoutValidation($data->getDataAsMatrix()->transpose(), 4, 30);
$ho->setClassifier($elm);
$ho->validate();
echo $ho->getConfusionMatrix() . "\n";
echo $ho->getPrecision() . "%\n";

echo "\n\nPerceptron\n";
$ho = new HoldoutValidation($data->getDataAsMatrix()->transpose(), 4, 30);
$ho->setClassifier($pcptron);
$ho->validate();
echo $ho->getConfusionMatrix() . "\n";
echo $ho->getPrecision() . "%\n";