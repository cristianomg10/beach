<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/1/16
 * Time: 12:24 PM
 */

namespace App\ANN\Examples;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../vendor/autoload.php');

use App\ActivationFunctions\RoundedSigmoidalFunction;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;

use App\ActivationFunctions\SigmoidalFunction;
use App\ActivationFunctions\StepFunction;
use App\ANN\SingleLayerPerceptron;

$slp = new SingleLayerPerceptron(3, new SigmoidalFunction(), new SigmoidalFunction(), 01.2, 0.8, 2, 1, 200);

$input			= new Matrix([
    [0, 0, 1, 1],
    [0, 1, 0, 1]
]);

$expectedOutput = new Vector([1, 0, 0, 1]);

$slp->setInput($input);
$slp->setExpectedOutput($expectedOutput);
$slp->train();