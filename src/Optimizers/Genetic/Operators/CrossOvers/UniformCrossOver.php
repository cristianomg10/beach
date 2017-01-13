<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 3:20 PM
 */

namespace App\Optimizers\Genetic\Operators\CrossOvers;

use App\Optimizers\Genetic\Operators\Elements\BinaryChromosome;
use App\Utils\Exceptions\IllegalArgumentException;
use App\Utils\Math;


class UniformCrossOver implements ICrossOver
{

    public function crossOver($individual1, $individual2)
    {
        if (!is_a($individual1, BinaryChromosome::class) || !is_a($individual2, BinaryChromosome::class)){
            throw new IllegalArgumentException("Individuals are not Chromosomes.");
        }

        $uniformArray = [];
        for ($i = 0; $i < $individual1->getLength(); ++$i){
            $uniformArray[] = round(Math::getRandomValue());
        }

        $newIndividual1 = new BinaryChromosome();
        $newIndividual2 = new BinaryChromosome();

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