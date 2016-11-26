<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/26/16
 * Time: 1:30 PM
 */

use App\ANN\ExtremeLearningMachine;
use App\Functions\ActivationFunctions\SigmoidalFunction;
use App\Utils\DataHandler\CSVDataHandler;
use App\Utils\Validations\HoldoutValidation;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../../vendor/autoload.php');

$data = new CSVDataHandler(0);
$data->open('../../DataHandler/Datasets/iris.csv');

$ho = new HoldoutValidation($data->getDataAsMatrix()->transpose(), 4);
$ho->setClassifier(new ExtremeLearningMachine(20, 3, new SigmoidalFunction()));
$ho->validate();
echo $ho->getConfusionMatrix() . "\n";
echo $ho->getPrecision() . "%";
