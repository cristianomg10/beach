<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/23/16
 * Time: 1:23 PM
 */

namespace App\Utils\Functions\ObjectiveFunctions;

// For Genetic
class RastriginFunction implements IObjectiveFunction
{
    public function compute($individual)
    {
        $genes = $individual->getGenes();
        $x[0] = bindec(implode("", array_slice($genes, 0, 4)));
        $x[1] = bindec(implode("", array_slice($genes, 4, 4)));
        $A = 10;
        $f = $A * 2;

        for ($i = 0; $i < 2; ++$i){
            $f += ($x[$i] ** 2) - $A * cos(2 * M_PI * $x[$i]);
        }

        return $f;
    }
}