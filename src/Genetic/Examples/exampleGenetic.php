<?php

use App\Genetic\Operators\BitByBitMutation;
use App\Genetic\Operators\RandomMutation;
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
    [0,0,0,0,0,0,0,0,0,0],
    [1,1,1,1,1,1,1,1,1,1]
); //working fine
var_dump($newOnes);

$k = new TwoPointCrossOver();
$newOnes = $k->crossOver(
    [0,0,0,0,0,0,0,0,0,0],
    [1,1,1,1,1,1,1,1,1,1]
);
var_dump($newOnes);

$k = new UniformCrossOver();
$newOnes = $k->crossOver(
    [0,0,0,0,0,0,0,0,0,0],
    [1,1,1,1,1,1,1,1,1,1]
);
var_dump($newOnes);

$k = new BitByBitMutation();
var_dump($k->mutate([0,0,0,0,0,0,0,0,0,0]));

$k = new RandomMutation();
var_dump($k->mutate([0,0,0,0,0,0,0,0,0,0]));