<?php

use App\Genetic\Chromosome;
use App\Genetic\Genetic;
use App\Genetic\ObjectiveFunctions\ArbitraryFunction;
use App\Genetic\Operators\BitByBitMutation;
use App\Genetic\Operators\RandomMutation;
use App\Genetic\Operators\RouletteWheelSelection;
use App\Genetic\Operators\SinglePointCrossOver;
use App\Genetic\Operators\TwoPointCrossOver;
use App\Genetic\Operators\UniformCrossOver;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

$k = new SinglePointCrossOver();
$newOnes = $k->crossOver(
    new Chromosome([0,0,0,0,0,0,0,0,0,0]),
    new Chromosome([1,1,1,1,1,1,1,1,1,1])
); //working fine
var_dump($newOnes);

$k = new TwoPointCrossOver();
$newOnes = $k->crossOver(
    new Chromosome([0,0,0,0,0,0,0,0,0,0]),
    new Chromosome([1,1,1,1,1,1,1,1,1,1])
);
var_dump($newOnes);

$k = new UniformCrossOver();
$newOnes = $k->crossOver(
    new Chromosome([0,0,0,0,0,0,0,0,0,0]),
    new Chromosome([1,1,1,1,1,1,1,1,1,1])
);
var_dump($newOnes);

$k = new BitByBitMutation();
var_dump($k->mutate(new Chromosome([0,0,0,0,0,0,0,0,0,0])));

$k = new RandomMutation();
var_dump($k->mutate(new Chromosome([0,0,0,0,0,0,0,0,0,0])));

$z = new Chromosome([0,0,0,0,0,0,0,0,0,0]);
$y = new Chromosome([1,1,1,1,1,1,1,1,1,1]);

$k = new TwoPointCrossOver();
var_dump($k->crossOver($z, $y));

echo (new ArbitraryFunction())->compute(new Chromosome([0,0,0,1,1,1,1,1]));

$g = new Genetic(40, 10, 0.85, 0.4, new ArbitraryFunction(), new RouletteWheelSelection(), new SinglePointCrossOver(), new BitByBitMutation());
$result = $g->run();

var_dump($result);