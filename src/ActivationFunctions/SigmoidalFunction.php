<?php
namespace App\ActivationFunctions;

use App\ActivationFunctions\IActivationFunction;

/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/25/16
 * Time: 3:57 PM
 */
class SigmoidalFunction implements IActivationFunction
{
    public function activationFunction($value)
    {
        return 1 / (1 + M_E ^ (- $value));
    }
}