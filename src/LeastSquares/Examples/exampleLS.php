<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/1/16
 * Time: 12:38 PM
 */

namespace App\LeastSquares\Examples;

use App\LeastSquares\DeterministicLeastSquares;
use App\LeastSquares\StochasticLeastSquares;
use App\Utils\DataHandler\CSVDataHandler;
use App\Utils\Validations\CrossValidation;
use MathPHP\LinearAlgebra\Matrix;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../vendor/autoload.php');

$output = [1,1,1,1,1,-1,-1,-1,-1,-1];
$input  = [
    [0.4, 0.5, 1],
    [0.6, 0.5, 1],
    [0.1, 0.4, 1],
    [0.2, 0.7, 1],
    [0.3, 0.3, 1],
    [0.4, 0.6, 1],
    [0.6, 0.2, 1],
    [0.7, 0.4, 1],
    [0.8, 0.6, 1],
    [0.7, 0.5, 1],
];

/*$dls = new DeterministicLeastSquares();
$dls->setInput(new Matrix($input));
$dls->setExpectedOutput(new Matrix([$output]));
$dls->learn();

// if bigger than 0, then 1, else -1
echo $dls->classify(new Matrix([
    [0.4, 0.6]
]));*/

//Stochastic;

$sls = new StochasticLeastSquares(100, 0, 0.001, 0.00005);
$sls->setInput((new Matrix($input))->transpose());
$sls->setExpectedOutput(new Matrix([$output]));
$sls->learn();

// if bigger than 0, then 1, else -1
echo $sls->classify(new Matrix([
    [0.2],[0.7], [1]
]));
/*
$output = [1,1,1,1,1,-1,-1,-1,-1,-1];
$input  = new Matrix([
    [0.4, 0.5, 1 , 1],
    [0.6, 0.5, 1 , 1],
    [0.1, 0.4, 1 , 1],
    [0.2, 0.7, 1 , 1],
    [0.3, 0.3, 1 , 1],
    [0.4, 0.6, 1 , -1],
    [0.6, 0.2, 1 ,  -1],
    [0.7, 0.4, 1 , -1],
    [0.8, 0.6, 1 , -1],
    [0.7, 0.5, 1 , -1],
]);


$cv = new CrossValidation($input, 3);
$cv->setClassifier($sls);
$cv->validate();
echo $cv->getConfusionMatrix();*/
