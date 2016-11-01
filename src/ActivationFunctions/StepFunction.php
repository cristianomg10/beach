<?php
namespace App\ActivationFunctions;

use App\ActivationFunctions\IActivationFunction;

/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/25/16
 * Time: 3:59 PM
 */
class StepFunction implements IActivationFunction
{
    public function activationFunction($value)
    {
        return ($value >= 0.5 ? 1 : 0);
    }
}