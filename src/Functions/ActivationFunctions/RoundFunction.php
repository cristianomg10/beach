<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/8/16
 * Time: 11:17 PM
 */


namespace App\Functions\ActivationFunctions;


class RoundFunction implements  IActivationFunction
{

    public function compute($value)
    {
        if ($value < 0) return 0;

        return round($value);
    }

    public function derivative($value)
    {
        return 0;
    }
}