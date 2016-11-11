<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 3:20 PM
 */

namespace App\Genetic\Operators;

use App\Genetic\Chromosome;
use App\Utils\Math;


class UniformCrossOver implements ICrossOver
{

    public function crossOver($individual1, $individual2)
    {
        if (!is_a($individual1, Chromosome::class) || !is_a($individual2, Chromosome::class)){
            throw new IllegalArgumentException("Individuals are not Chromosomes.");
        }

        $uniformArray = [];
        for ($i = 0; $i < $individual1->getLength(); ++$i){
            $uniformArray[] = round(Math::getRandomValue());
        }

        $newIndividual1 = new Chromosome();
        $newIndividual2 = new Chromosome();

        for ($i = 0; $i < $individual1->getLength(); ++$i){
            if ($uniformArray[$i] == 0){
                $newIndividual1->updateGenes($i, $individual1->getGene($i));
                $newIndividual2->updateGenes($i, $individual2->getGene($i));
            } else {
                $newIndividual1->updateGenes($i, $individual2->getGene($i));
                $newIndividual2->updateGenes($i, $individual1->getGene($i));
            }
        }

        return [$newIndividual1, $newIndividual2];
    }
}