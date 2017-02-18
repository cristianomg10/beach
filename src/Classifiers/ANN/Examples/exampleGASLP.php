<?php

use App\Classifiers\ANN\SingleLayerPerceptron;
use App\Utils\DataHandler\CSVDataHandler;
use App\Utils\Functions\ActivationFunctions\RoundedSigmoidalFunction;
use App\Utils\Functions\ActivationFunctions\SigmoidalFunction;
use App\Utils\Normalization\Normalization;
use App\Utils\Validations\HoldoutValidation;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../../vendor/autoload.php');

$data = new CSVDataHandler(0);
$data->open('../../../Utils/DataHandler/Datasets/iris.csv');

$n  = new Normalization($data->getDataAsMatrix(), -1, 1);

$d = $data->getDataAsMatrix()->transpose();
$ho = new HoldoutValidation($data->getDataAsMatrix()->transpose(), 4, 10);

$slp = new SingleLayerPerceptron(
    3, new SigmoidalFunction(), new RoundedSigmoidalFunction(), 0.5, 0.2,
    $ho->getUnlabeledDataForTraining()->getM(), 1, 10);

$k = $ho->getUnlabeledDataForTraining();
$l = $ho->getLabelForTraining();

for ($i = 0; $i < $k->getN(); ++$i){
    $error[] = $l->get(0, $i) - $slp->run($k->getColumn($i), 2);
}
