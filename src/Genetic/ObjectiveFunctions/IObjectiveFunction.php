<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/14/16
 * Time: 11:37 AM
 */

namespace App\Genetic\ObjectiveFunctions;


use App\Genetic\Chromosome;

interface IObjectiveFunction
{
    public function compute(Chromosome $individual);
}