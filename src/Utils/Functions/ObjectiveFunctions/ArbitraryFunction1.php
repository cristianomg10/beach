<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/17/16
 * Time: 11:03 PM
 */

namespace App\Utils\Functions\ObjectiveFunctions;

//For DE and PSO
class ArbitraryFunction1 implements  IObjectiveFunction
{

    public function compute($x)
    {
        return (2.8125 - $x[0] + $x[0] * ($x[1]**4)) ** 2 + (2.25 - $x[0] + $x[0] * ($x[1] ** 2)) ** 2 + (1.5 - $x[0] + $x[0] * $x[1]) ** 2;
    }
}