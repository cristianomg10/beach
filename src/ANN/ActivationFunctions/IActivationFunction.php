<?php
namespace App\ANN\ActivationFunctions;

/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/25/16
 * Time: 3:54 PM
 */
interface IActivationFunction
{
    public function compute($value);
    public function derivative($value);
}