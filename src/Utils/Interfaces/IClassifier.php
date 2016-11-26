<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/25/16
 * Time: 4:12 PM
 */

namespace App\Utils\Interfaces;


use MathPHP\LinearAlgebra\Matrix;

interface IClassifier
{
    public function setInput(Matrix $input);
    public function setExpectedOutput(Matrix $expectedOutput);
    public function learn();
    public function classify(Matrix $input);
}