<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/14/16
 * Time: 11:37 AM
 */

namespace App\Functions\ObjectiveFunctions;

interface IObjectiveFunction
{
    public function compute($individual);
}