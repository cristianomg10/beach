<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/29/16
 * Time: 3:55 PM
 */

namespace App\Optimizers\Genetic\Operators\CrossOvers;

use App\Optimizers\Genetic\Operators\Elements\BinaryChromosome;
use App\Optimizers\Genetic\Operators\Elements\PermutationChromosome;
use App\Utils\Exceptions\IllegalArgumentException;
use App\Utils\Math;

class OrderBasedCrossOver implements ICrossOver
{

    public function crossOver($individual1, $individual2)
    {
        if (!is_a($individual1, PermutationChromosome::class) || !is_a($individual2, PermutationChromosome::class)){
            throw new IllegalArgumentException("Individuals are not Chromosomes.");
        }

        $length = $individual1->getLength();

        $selectionString = new BinaryChromosome();
        $selectionString->initialize($length);

        $newIndividual1 = $this->orderBasedCrossOver($selectionString, $individual1, $individual2);
        $newIndividual2 = $this->orderBasedCrossOver($selectionString, $individual2, $individual1);

        return [$newIndividual1, $newIndividual2];
    }

    private function orderBasedCrossOver($selectionString, $individual1, $individual2){

        $length = $individual1->getLength();

        // generating array populated with -1;
        $newIndividual = new PermutationChromosome(Math::sumNumberToMatrix(Math::generateRandomVector($length, 0), -1));

        $notTaken = [];
        for ($i = 0; $i < $length; ++$i){
            if ($selectionString->getGene($i) == 1) {
                $newIndividual->updateGenes($i, $individual1->getGene($i));
            } else {
                $notTaken[] = $individual1->getGene($i);
            }
        }

        $newNotTaken = [];
        for ($i = 0; $i < $length; ++$i){
            if (in_array($individual2->getGene($i), $notTaken)){
                $newNotTaken[] = $individual2->getGene($i);
            }
        }

        $index = 0;
        for ($i = 0; $i < $length; ++$i){
            if ($newIndividual->getGene($i) == -1){
                $newIndividual->updateGenes($i, $newNotTaken[$index]);
                ++$index;
            }
        }

        return $newIndividual;
    }
}