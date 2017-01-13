<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/29/16
 * Time: 5:41 PM
 */

namespace App\Optimizers\Genetic\Operators\Mutators;


use App\Optimizers\Genetic\Operators\Elements\BinaryChromosome;
use App\Optimizers\Genetic\Operators\Elements\PermutationChromosome;
use App\Utils\Exceptions\IllegalArgumentException;

class SublistShuffleMutation implements IMutation
{

    public function mutate($individual)
    {
        if (!is_a($individual, BinaryChromosome::class) && !is_a($individual, PermutationChromosome::class)){
            throw new IllegalArgumentException("Individuals are not Chromosomes.");
        }

        $genes = $individual->getGenes();

        $length = $individual->getLength();
        $point1 = rand(0, $length / 2);
        $point2 = rand($point1, $length - 1);

        $sublist = array_slice($genes, $point1, $point2 - $point1 + 1);
        shuffle($sublist);

        $index = 0;
        for ($i = $point1; $i <= $point2; ++$i){
            $genes[$i] = $sublist[$index];
            ++$index;
        }

        $individual->setGenes($genes);
        return $individual;
    }
}