<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/26/16
 * Time: 1:30 PM
 */

use App\ANN\ExtremeLearningMachine;
use App\Utils\DataHandler\CSVDataHandler;
use App\Utils\Functions\ActivationFunctions\SigmoidalFunction;
use App\Utils\Validations\CrossValidation;
use App\Utils\Validations\HoldoutValidation;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../../vendor/autoload.php');

$data = new CSVDataHandler(0);
$data->open('../../DataHandler/Datasets/iris.csv');

$elm = new ExtremeLearningMachine(30, 3, new SigmoidalFunction());

$ho = new HoldoutValidation($data->getDataAsMatrix()->transpose(), 4, 30);
$ho->setClassifier($elm);
$ho->validate();
echo $ho->getConfusionMatrix() . "\n";
echo $ho->getPrecision() . "%\n";
die();

$ho = new CrossValidation($data->getDataAsMatrix()->transpose(), 4, 10);
$ho->setClassifier($elm);
$ho->validate();
echo $ho->getConfusionMatrix() . "\n";
echo $ho->getPrecision() . "%";