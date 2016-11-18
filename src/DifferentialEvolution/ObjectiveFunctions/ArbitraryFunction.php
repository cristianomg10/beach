<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/17/16
 * Time: 11:03 PM
 */

namespace App\DifferentialEvolution\ObjectiveFunctions;


class ArbitraryFunction implements  IObjectiveFunction
{

    public function compute($x)
    {
        return (2.8125 - $x[0] + $x[0] * ($x[1]**4)) ** 2 + (2.25 - $x[0] + $x[0] * ($x[1] ** 2)) ** 2 + (1.5 - $x[0] + $x[0] * $x[1]) ** 2;
    }
}