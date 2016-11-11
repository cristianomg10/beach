<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 3:20 PM
 */

namespace App\Genetic\Operators;

use App\Utils\Math;


class UniformCrossOver implements ICrossOver
{

    public function crossOver($individual1, $individual2)
    {
        $uniformArray = [];
        for ($i = 0; $i < count($individual1); ++$i){
            $uniformArray[] = round(Math::getRandomValue());
        }

        $newIndividual1 = [];
        $newIndividual2 = [];

        for ($i = 0; $i < count($individual1); ++$i){
            if ($uniformArray[$i] == 0){
                $newIndividual1[] = $individual1[$i];
                $newIndividual2[] = $individual2[$i];
            } else {
                $newIndividual1[] = $individual2[$i];
                $newIndividual2[] = $individual1[$i];
            }
        }

        return [$newIndividual1, $newIndividual2];
    }
}