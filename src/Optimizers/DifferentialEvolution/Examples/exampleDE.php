<?php

use App\Optimizers\DifferentialEvolution\DifferentialEvolution;
use App\Utils\Functions\ObjectiveFunctions\Arbitrary1Function;
use App\Utils\Functions\ObjectiveFunctions\SphereFunction;
use App\Utils\Functions\ObjectiveFunctions\TestFunction;
use App\Optimizers\DifferentialEvolution\Strategies\DEBest2Strategy;
use App\Optimizers\DifferentialEvolution\Strategies\DECurrent2Best1Strategy;
use App\Optimizers\DifferentialEvolution\Strategies\DERand1Strategy;
use App\Optimizers\DifferentialEvolution\Strategies\DEBest1Strategy;
use App\Optimizers\DifferentialEvolution\Strategies\DERand2Best1Strategy;
use App\Optimizers\DifferentialEvolution\Strategies\DERand2Strategy;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../../vendor/autoload.php');

// For minimization
$de = new DifferentialEvolution(new SphereFunction(), new DECurrent2Best1Strategy(), 10, 50, 100, 1, 0.9, 0.5, 1.1);
$de->run();
var_dump("Best individual:", $de->getBest());
var_dump("Best fitness: ", (new SphereFunction())->compute($de->getBest()));