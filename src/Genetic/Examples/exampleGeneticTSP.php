<?php


use App\Genetic\Operators\PermutationChromosome;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

$pc = new PermutationChromosome();
$pc->initialize(8);

