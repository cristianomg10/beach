<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 2/18/17
 * Time: 12:07 PM
 */

use App\Classifiers\ANN\SingleLayerPerceptron;
use App\Optimizers\Genetic\GeneticANNTrain;
use App\Optimizers\Genetic\Operators\CrossOvers\FlatCrossOver;
use App\Optimizers\Genetic\Operators\Mutators\SwapMutation;
use App\Optimizers\Genetic\Operators\Selectors\TournamentSelection;
use App\Utils\DataHandler\CSVDataHandler;
use App\Utils\Functions\ActivationFunctions\RoundedSigmoidalFunction;
use App\Utils\Functions\ActivationFunctions\SigmoidalFunction;
use App\Utils\Functions\ObjectiveFunctions\SLPFunction;
use App\Utils\Normalization\Normalization;
use App\Utils\Validations\HoldoutValidation;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
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


$g = new GeneticANNTrain(15, 200, 0.7, 0.1,
    new SLPFunction($slp), new TournamentSelection(), new FlatCrossOver(),
                         new SwapMutation(), $k, $l, $slp->getWeightSize());

$g->run();