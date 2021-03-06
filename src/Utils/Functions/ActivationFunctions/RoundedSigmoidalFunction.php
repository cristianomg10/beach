<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/5/16
 * Time: 11:24 AM
 */


namespace App\Utils\Functions\ActivationFunctions;


class RoundedSigmoidalFunction implements IActivationFunction
{
    public function compute($value)
    {
        return round(1 / (1 + M_E ^ (- $value)));
    }

    public function derivative($value){
        return  (M_E ^ (-$value)) / ((1 + M_E ^ (-$value)) **2);
    }

}