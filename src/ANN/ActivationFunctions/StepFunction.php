<?php
namespace App\ANN\ActivationFunctions;

/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/25/16
 * Time: 3:59 PM
 */
class StepFunction implements IActivationFunction
{
    public function compute($value)
    {
        return ($value >= 0.5 ? 1 : 0);
    }

    public function derivative($value)
    {
        if ($value == 0.5) {
            return null;
        }
        return 0;
    }
}