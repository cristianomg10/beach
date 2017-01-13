<?php


use App\Optimizers\ArtificialBeeColony\ArtificialBeeColony;
use App\Optimizers\ArtificialBeeColony\Operators\RouletteWheelSelection;
use App\Utils\Functions\ObjectiveFunctions\Arbitrary1Function;
use App\Utils\Loggable\TerminalLoggable;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../../vendor/autoload.php');

$abc = new ArtificialBeeColony(50, 50, 100, 2, new Arbitrary1Function, -10, 10, new TerminalLoggable(), new RouletteWheelSelection);
$abc->run();
var_dump($abc->getBest());