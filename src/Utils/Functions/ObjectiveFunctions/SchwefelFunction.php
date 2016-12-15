<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/8/16
 * Time: 3:52 PM
 */

namespace App\Utils\Functions\ObjectiveFunctions;


class SchwefelFunction implements IObjectiveFunction
{

    public function compute($individual)
    {
        $d = count($individual);
        $sum = 0;

        for ($i = 0; $i < $d; ++$i) {
            $sum += -$individual[$i] * sin(sqrt(abs($individual[$i])));
        }

        return 418.9829 * $d - $sum;
    }
}