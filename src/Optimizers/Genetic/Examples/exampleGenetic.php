<?php

use App\Optimizers\Genetic\Genetic;
use App\Optimizers\Genetic\Operators\CrossOvers\FlatCrossOver;
use App\Optimizers\Genetic\Operators\CrossOvers\SinglePointCrossOver;
use App\Optimizers\Genetic\Operators\CrossOvers\TwoPointCrossOver;
use App\Optimizers\Genetic\Operators\CrossOvers\UniformCrossOver;
use App\Optimizers\Genetic\Operators\Elements\BinaryChromosome;
use App\Optimizers\Genetic\Operators\Elements\FloatChromosome;
use App\Optimizers\Genetic\Operators\Mutators\BitByBitMutation;
use App\Optimizers\Genetic\Operators\Mutators\RandomMutation;
use App\Optimizers\Genetic\Operators\Selectors\RouletteWheelSelection;
use App\Utils\Functions\ObjectiveFunctions\ArbitraryFunction;
use App\Utils\Functions\ObjectiveFunctions\EasomFunction;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../../vendor/autoload.php');

$k = new SinglePointCrossOver();
$newOnes = $k->crossOver(
    new BinaryChromosome([0,0,0,0,0,0,0,0,0,0]),
    new BinaryChromosome([1,1,1,1,1,1,1,1,1,1])
); //working fine
var_dump($newOnes);

$k = new TwoPointCrossOver();
$newOnes = $k->crossOver(
    new BinaryChromosome([0,0,0,0,0,0,0,0,0,0]),
    new BinaryChromosome([1,1,1,1,1,1,1,1,1,1])
);
var_dump($newOnes);

$k = new UniformCrossOver();
$newOnes = $k->crossOver(
    new BinaryChromosome([0,0,0,0,0,0,0,0,0,0]),
    new BinaryChromosome([1,1,1,1,1,1,1,1,1,1])
);
var_dump($newOnes);

$k = new BitByBitMutation();
var_dump($k->mutate(new BinaryChromosome([0,0,0,0,0,0,0,0,0,0])));

$k = new RandomMutation();
var_dump($k->mutate(new BinaryChromosome([0,0,0,0,0,0,0,0,0,0])));

$z = new BinaryChromosome([0,0,0,0,0,0,0,0,0,0]);
$y = new BinaryChromosome([1,1,1,1,1,1,1,1,1,1]);

$k = new TwoPointCrossOver();
var_dump($k->crossOver($z, $y));

echo (new ArbitraryFunction())->compute(new BinaryChromosome([0,0,0,1,1,1,1,1]));

$g = new Genetic(40, 10, 0.85, 0.4, new EasomFunction(), new RouletteWheelSelection(), new SinglePointCrossOver(), new BitByBitMutation(), 1, 'MIN');
$result = $g->run();

var_dump($result);

$k = new FloatChromosome();
$k->initialize(8);
echo "$k\n";
$j = new FloatChromosome();
$j->initialize(8);
echo "$j\n";
$s = (new FlatCrossOver())->crossOver($k, $j);
echo $s[0] . "\n";
echo $s[1] . "\n";