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

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;

use App\ActivationFunctions\SigmoidalFunction;
use App\ActivationFunctions\StepFunction;
use App\ANN\SingleLayerPerceptron;

$slp = new SingleLayerPerceptron(3, new SigmoidalFunction(), new StepFunction(), 0.7, 0.3, 2, 1, 100);

$input			= new Matrix([
    [0, 0, 1, 1],
    [0, 1, 0, 1]
]);

$expectedOutput = new Vector([1, 0, 0, 1]);

$A = new Matrix([[1, 2], [3, 4]]);
$B = new Matrix([[-1, 3], [4, 2]]);

$slp->setInput($input);
$slp->setExpectedOutput($expectedOutput);
$slp->train();