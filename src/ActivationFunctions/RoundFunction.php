<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/8/16
 * Time: 11:17 PM
 */

namespace App\ActivationFunctions;


class RoundFunction implements  IActivationFunction
{

    public function compute($value)
    {
        return round($value);
    }

    public function derivative($value)
    {
        return 0;
    }
}