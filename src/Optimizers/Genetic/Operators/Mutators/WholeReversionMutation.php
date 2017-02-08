<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 2/8/17
 * Time: 10:24 AM
 */

namespace App\Optimizers\Genetic\Operators\Mutators;


use App\Optimizers\Genetic\Operators\Elements\BinaryChromosome;
use App\Optimizers\Genetic\Operators\Elements\PermutationChromosome;
use App\Utils\Exceptions\IllegalArgumentException;

class WholeReversionMutation implements IMutation
{

    public function mutate($individual)
    {
        if (!is_a($individual, BinaryChromosome::class) && !is_a($individual, PermutationChromosome::class)){
            throw new IllegalArgumentException("Individual is not a Chromosome.");
        }

        $reversedGenes = array_reverse($individual->getGenes());

        $individual->setGenes($reversedGenes);

        return $individual;
    }
}