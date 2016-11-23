<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/22/16
 * Time: 5:33 PM
 */

namespace App\ParticleSwarmOptimization\ObjectiveFunctions;


class ArbitraryFunction implements IObjectiveFunction
{

    public function compute($x)
    {
        return (2.8125 - $x[0] + $x[0] * ($x[1]**4)) ** 2 + (2.25 - $x[0] + $x[0] * ($x[1] ** 2)) ** 2 + (1.5 - $x[0] + $x[0] * $x[1]) ** 2;
    }
}