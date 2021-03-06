<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/28/16
 * Time: 3:25 PM
 */

namespace App\Optimizers\Genetic\Operators\Mutators;

use App\Optimizers\Genetic\Operators\Elements\BinaryChromosome;
use App\Optimizers\Genetic\Operators\Elements\FloatChromosome;
use App\Optimizers\Genetic\Operators\Elements\PermutationChromosome;
use App\Utils\Exceptions\IllegalArgumentException;

class SwapMutation implements IMutation
{
    public function mutate($individual)
    {
        if (!is_a($individual, BinaryChromosome::class) && !is_a($individual, PermutationChromosome::class)
        && !is_a($individual, FloatChromosome::class)){
            throw new IllegalArgumentException("Individual is not a Chromosome.");
        }

        $position1 = rand(0, $individual->getLength() / 2);
        $position2 = rand($position1, $individual->getLength() - 1);

        while ($position1 == $position2) $position2 = rand(0, $individual->getLength() - 1);

        $value1 = $individual->getGene($position1);
        $individual->updateGenes($position1, $individual->getGene($position2));
        $individual->updateGenes($position2, $value1);

        return $individual;
    }
}