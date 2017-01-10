<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 1/9/17
 * Time: 9:06 PM
 */

namespace App\Genetic\Operators\CrossOvers;


use App\Genetic\Operators\Elements\FloatChromosome;
use App\Utils\Exceptions\IllegalArgumentException;
use App\Utils\Math;

class FlatCrossOver implements ICrossOver
{

    public function crossOver($individual1, $individual2)
    {
        /*echo !is_a(FloatChromosome::class, $individual1);
        if (!is_a('FloatChromosome', $individual1) && !is_a('FloatChromosome', $individual2)){
            throw new IllegalArgumentException("Individuals must be instance of FloatChromosome.");
        }*/

        $newIndividual1 = new FloatChromosome();
        $newIndividual2 = new FloatChromosome();
        $length = $individual1->getLength();

        $newIndividual1->initialize($length);
        $newIndividual2->initialize($length);

        for ($i = 0; $i < $length; ++$i){
            $values = [$individual1->getGene($i), $individual2->getGene($i)];
            sort($values);

            $smaller = $values[0];
            $bigger  = $values[1];

            $number1 = Math::getRandomValue()  * ($bigger - $smaller) + $smaller;
            $number2 = Math::getRandomValue()  * ($bigger - $smaller) + $smaller;

            $newIndividual1->updateGenes($i, $number1);
            $newIndividual2->updateGenes($i, $number2);
        }

        return [$newIndividual1, $newIndividual2];
    }
}