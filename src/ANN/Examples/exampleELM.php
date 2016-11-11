<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/7/16
 * Time: 11:49 AM
 */

namespace App\ANN\Examples;


error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__ . '/../../../vendor/autoload.php');

use App\ActivationFunctions\SigmoidalFunction;
use App\ANN\ExtremeLearningMachine;
use MathPHP\LinearAlgebra\Matrix;

$elm = new ExtremeLearningMachine(20, 3, new SigmoidalFunction());
$elm->setInput(new Matrix([[0,0,1,1],[0,1,0,1]]));
$elm->setExpectedOutput(new Matrix([[0,1,1,0]]));
$elm->setActivationFunction(new SigmoidalFunction());
$elm->learn();

$inputForTest = new Matrix([[0, 1], [1, 1], [0, 0], [1, 0], [0, 0]]);
$values = $elm->classify($inputForTest);
echo $values . "<BR>";

$elm = new ExtremeLearningMachine(20, 3, new SigmoidalFunction());
$elm->setInput(new Matrix([[0,0,1,1],[0,1,0,1]]));
$elm->setExpectedOutput(new Matrix([[0,1,1,0]]));
$elm->setActivationFunction(new SigmoidalFunction());
$elm->learn();

$inputForTest = new Matrix([[0, 1], [1, 1], [0, 0], [1, 0], [0, 0]]);
$values = $elm->classify($inputForTest);
echo $values;