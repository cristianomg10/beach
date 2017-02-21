<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 2/18/17
 * Time: 12:07 PM
 */

use App\Classifiers\ANN\SingleLayerPerceptron;
use App\Optimizers\DifferentialEvolution\DifferentialEvolution;
use App\Optimizers\DifferentialEvolution\Strategies\DECurrent2Best1Strategy;
use App\Optimizers\Genetic\GeneticANNTrain;
use App\Optimizers\Genetic\Operators\CrossOvers\FlatCrossOver;
use App\Optimizers\Genetic\Operators\CrossOvers\SinglePointCrossOver;
use App\Optimizers\Genetic\Operators\Elements\FloatChromosome;
use App\Optimizers\Genetic\Operators\Mutators\BiasedMutation;
use App\Optimizers\Genetic\Operators\Mutators\InputNodeMutation;
use App\Optimizers\Genetic\Operators\Mutators\SwapMutation;
use App\Optimizers\Genetic\Operators\Mutators\UnbiasedMutation;
use App\Optimizers\Genetic\Operators\Selectors\RouletteWheelSelection;
use App\Optimizers\Genetic\Operators\Selectors\TournamentSelection;
use App\Optimizers\ParticleSwarmOptimization\ParticleSwarmOptimization;
use App\Utils\DataHandler\CSVDataHandler;
use App\Utils\Functions\ActivationFunctions\RoundedSigmoidalFunction;
use App\Utils\Functions\ActivationFunctions\RoundFunction;
use App\Utils\Functions\ActivationFunctions\SigmoidalFunction;
use App\Utils\Functions\ObjectiveFunctions\SLPFunction;
use App\Utils\Loggable\TerminalLoggable;
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

$data->setNormalization($n);
$d = $data->getDataAsMatrix()->transpose();

$ho = new HoldoutValidation($data->getDataAsMatrix()->transpose(), 4, 10);
$data->normalize(0);
$data->normalize(1);
$data->normalize(2);
$data->normalize(3);

$slp = new SingleLayerPerceptron(
    2, new SigmoidalFunction(), new RoundFunction(), 0.5, 0.2,
    $ho->getUnlabeledDataForTraining()->getM(), 1, 10);

$k = $ho->getUnlabeledDataForTraining();
$l = $ho->getLabelForTraining();


/*$g = new GeneticANNTrain(100, 200, 0.7, 0.3,
    new SLPFunction($slp, $k, $l), new RouletteWheelSelection(), new SinglePointCrossOver(),
                         new BiasedMutation(0.8), new TerminalLoggable(), $slp->getWeightSize(), 1, 'MIN');
*/
//$g = new ParticleSwarmOptimization(100, 200, 2, 2, $slp->getWeightSize(), new SLPFunction($slp, $k, $l), 1, 0.989, -5, 5, new TerminalLoggable());
//$g = new DifferentialEvolution(new SLPFunction($slp, $k, $l), new DECurrent2Best1Strategy(), $slp->getWeightSize(), 1500, 50, 1, 0.9, new TerminalLoggable(), -5, 5, 0.7, 1.1);

//$g->run();
//var_dump($g->getBest());
//echo get_class($g) . "\n";

$k = new FloatChromosome([0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8]);

$l = new InputNodeMutation(3, 2);
var_dump($l->mutate($k));
