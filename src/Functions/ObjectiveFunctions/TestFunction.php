<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/17/16
 * Time: 10:33 PM
 */

namespace App\Functions\ObjectiveFunctions;


// For DE
class TestFunction implements IObjectiveFunction
{

    public function compute($x)
    {
        return ($x[0] - 5) ** 2 + ($x[1] + 15) ** 2;
    }
}