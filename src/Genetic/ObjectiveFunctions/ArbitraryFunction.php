<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/14/16
 * Time: 11:38 AM
 */

namespace App\Genetic\ObjectiveFunctions;


use App\Genetic\Chromosome;

class ArbitraryFunction implements IObjectiveFunction
{
    public function compute(Chromosome $individual)
    {
        $genes = $individual->getGenes();
        $x = bindec(implode("", array_slice($genes, 0, 4)));
        $y = bindec(implode("", array_slice($genes, 4, 4)));

        return $x + $y;
    }
}