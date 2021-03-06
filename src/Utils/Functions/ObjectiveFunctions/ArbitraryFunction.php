<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/14/16
 * Time: 11:38 AM
 */


namespace App\Utils\Functions\ObjectiveFunctions;

//For Genetic
use App\Optimizers\Genetic\Operators\Elements\BinaryChromosome;

class ArbitraryFunction implements IObjectiveFunction
{
    public function compute($individual)
    {
        if ($individual instanceof BinaryChromosome) {
            $genes = $individual->getGenes();
            $x = bindec(implode("", array_slice($genes, 0, 4)));
            $y = bindec(implode("", array_slice($genes, 4, 4)));

        }else if (is_array($individual)){
            $x = array_slice($individual, 0, 4);
            $y = array_slice($individual, 4, 4);
        }

        return $x + $y;
    }
}