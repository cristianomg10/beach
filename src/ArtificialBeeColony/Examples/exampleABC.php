<?php


use App\ArtificialBeeColony\ArtificialBeeColony;
use App\ArtificialBeeColony\Operators\RouletteWheelSelection;
use App\Functions\ObjectiveFunctions\ArbitraryFunction1;
use App\Loggable\TerminalLoggable;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

$abc = new ArtificialBeeColony(50, 50, 100, 2, new ArbitraryFunction1, -10, 10, new TerminalLoggable(), new RouletteWheelSelection);
$abc->run();
var_dump($abc->getBestBee());