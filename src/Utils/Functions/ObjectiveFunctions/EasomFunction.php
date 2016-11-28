<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/23/16
 * Time: 3:00 PM
 */

namespace App\Utils\Functions\ObjectiveFunctions;


use App\Genetic\Chromosome;

class EasomFunction implements IObjectiveFunction
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

        return -cos($x[0]) * cos($x[1]) * exp(-(($x[0] - M_PI) ** 2 + ($x[1] - M_PI) ** 2)) + 1;
    }
}