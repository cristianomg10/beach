<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/22/16
 * Time: 5:33 PM
 */

namespace App\ParticleSwarmOptimization\ObjectiveFunctions;


interface IObjectiveFunction
{
    public function compute($x);
}