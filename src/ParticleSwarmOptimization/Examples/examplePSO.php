<?php

use App\ParticleSwarmOptimization\ObjectiveFunctions\ArbitraryFunction;
use App\ParticleSwarmOptimization\ParticleSwarmOptimization;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

$pso = new ParticleSwarmOptimization(50, 100, 2, 2, 2, new ArbitraryFunction(), 1, 0.99, -10, 10);
$pso->run();
var_dump($pso->getBestParticle()->getLocation());