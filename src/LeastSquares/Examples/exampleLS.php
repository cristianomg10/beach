<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/1/16
 * Time: 12:38 PM
 */

namespace App\LeastSquares\Examples;

use App\LeastSquares\DeterministicLeastSquares;
use MathPHP\LinearAlgebra\Matrix;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../vendor/autoload.php');

$output = [1,1,1,1,1,-1,-1,-1,-1,-1];
$input  = [
    [0.4, 0.5],
    [0.6, 0.5],
    [0.1, 0.4],
    [0.2, 0.7],
    [0.3, 0.3],
    [0.4, 0.6],
    [0.6, 0.2],
    [0.7, 0.4],
    [0.8, 0.6],
    [0.7, 0.5],
];

$dls = new DeterministicLeastSquares();
$dls->setInput(new Matrix($input));
$dls->setExpectedOutput(new Matrix([$output]));
$dls->learn();

// if bigger than 0, then 1, else -1
echo $dls->classify(new Matrix([
    [0.3, 0.3]
]));