<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/23/16
 * Time: 3:21 PM
 */

namespace App\Functions\ObjectiveFunctions;


class SphereFunction implements IObjectiveFunction
{

    public function compute($individual)
    {
        if (is_a($individual, Chromosome::class)) {
            $genes = $individual->getGenes();
            $x[0] = bindec(implode("", array_slice($genes, 0, 4)));
            $x[1] = bindec(implode("", array_slice($genes, 4, 4)));
        } else {
            $x = $individual;
        }

        $f = 0;
        foreach ($x as $i){
            $f += $i ** 2;
        }

        return $f;
    }
}