<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/22/16
 * Time: 2:07 PM
 */

namespace App\Optimizers\DifferentialEvolution\Strategies;


use App\Utils\Functions\ObjectiveFunctions\IObjectiveFunction;

interface IStrategy
{
    public function calculate($individuals, $probCrossOver, IObjectiveFunction $objectiveFunction, $F1, $F2 = 0);
}