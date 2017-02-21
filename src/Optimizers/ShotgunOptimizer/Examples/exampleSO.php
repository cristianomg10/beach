<?php

namespace App\Optimizers\ShotgunOptimizer\Examples;


use App\Optimizers\ShotgunOptimizer\ShotgunOptimizer;
use App\Utils\Functions\ObjectiveFunctions\SchwefelFunction;
use App\Utils\Loggable\TerminalLoggable;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../../vendor/autoload.php');

$so = new ShotgunOptimizer(50, 5000, 50, -500, 500, new SchwefelFunction(), new TerminalLoggable());
$so->run();
var_dump($so->getBest());