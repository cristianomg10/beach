<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/5/16
 * Time: 12:14 PM
 */

namespace App\Utils\Benchmarks;

use App\ArtificialBeeColony\ArtificialBeeColony;
use App\ArtificialBeeColony\Operators\RouletteWheelSelection;
use App\DifferentialEvolution\DifferentialEvolution;
use App\DifferentialEvolution\Strategies\DECurrent2Best1Strategy;
use App\Genetic\Genetic;
use App\Genetic\Operators\BitByBitMutation;
use App\Genetic\Operators\RouletteWheelSelection as GeneticRouletteWheelSelection;
use App\Genetic\Operators\TwoPointCrossOver;
use App\ParticleSwarmOptimization\ParticleSwarmOptimization;
use App\Utils\Functions\ObjectiveFunctions\RastriginFunction;
use App\Utils\Loggable\NotLoggable;
use App\Utils\Loggable\TerminalLoggable;
use MathPHP\LinearAlgebra\Matrix;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

$function = new RastriginFunction();

echo "\nArtificial Bee Colony: \n";
$abc = new ArtificialBeeColony(100, 100, 50, 10, $function, 0, 100, new NotLoggable(), new RouletteWheelSelection());
$t1 = microtime(true);
$abc->run();
$t2 = number_format(( microtime(true) - $t1), 4);
echo "Time spent: $t2 s\n";
echo "Bee's position: " . new Matrix([$abc->getBest()->getPosition()]) . "\n";
echo "Bee's fitness: " . $function->compute($abc->getBest()->getPosition()) . "\n\n";

echo "\nDifferential Evolution: \n";
$de = new DifferentialEvolution($function, new DECurrent2Best1Strategy(), 10, 100, 50, 2, 0.9, 2, 1.5);
$t1 = microtime(true);
$de->run();
$t2 = number_format(( microtime(true) - $t1), 4);
echo "Time spent: $t2 s\n";
echo "Individual's position: " . new Matrix([$de->getBest()->getData()]) . "\n";
echo "Individual's fitness: " . $function->compute($de->getBest()->getData()) . "\n\n";

echo "\nGenetic (2D): \n";
$ge = new Genetic(100, 50, 0.9, 0.35, $function, new GeneticRouletteWheelSelection(), new TwoPointCrossOver(), new BitByBitMutation(), 1, 'MIN');
$t1 = microtime(true);
$ge->run();
$t2 = number_format(( microtime(true) - $t1), 4);
echo "Time spent: $t2 s\n";
echo "Individual's position: " . new Matrix([$ge->getBest()->getGenes()]) . "\n";
echo "Individual's fitness: " . $function->compute($ge->getBest()) . "\n\n";

echo "\nPSO: \n";
$pso = new ParticleSwarmOptimization(100, 50, 2, 2, 10, $function, 0.5, 0.99, -3, 3, new NotLoggable());
$t1 = microtime(true);
$pso->run();
$t2 = number_format(( microtime(true) - $t1), 4);
echo "Time spent: $t2 s\n";
echo "Particle's position: " . new Matrix([$pso->getBest()->getLocation()]) . "\n";
echo "Particle's fitness: " . $function->compute($pso->getBest()->getLocation()) . "\n\n";