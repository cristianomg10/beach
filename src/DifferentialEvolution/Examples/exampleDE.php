<?php

use App\DifferentialEvolution\DifferentialEvolution;
use App\DifferentialEvolution\ObjectiveFunctions\ArbitraryFunction;
use App\DifferentialEvolution\ObjectiveFunctions\TestFunction;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

// For minimization
$de = new DifferentialEvolution(new ArbitraryFunction(), 2, 100, 500, 0.5, 5, 0.9);
$de->run();
var_dump("Best individual:", $de->getBest());
var_dump("Best fitness: ", (new ArbitraryFunction())->compute($de->getBest()->getData()));