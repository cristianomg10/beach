<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/17/16
 * Time: 9:57 PM
 */

namespace App\DifferentialEvolution\ObjectiveFunctions;


interface IObjectiveFunction
{
    public function compute($x);
}