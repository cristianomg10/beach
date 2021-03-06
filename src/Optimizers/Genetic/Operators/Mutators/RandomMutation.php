<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 4:27 PM
 */

namespace App\Optimizers\Genetic\Operators\Mutators;

use App\Optimizers\Genetic\Operators\Elements\BinaryChromosome;
use App\Utils\Exceptions\IllegalArgumentException;

class RandomMutation implements IMutation
{

    public function mutate($individual)
    {
        if (!is_a($individual, BinaryChromosome::class)){
            throw new IllegalArgumentException("Individual is not a Chromosome.");
        }

        $point = rand(0, $individual->getLength() - 1);

        $new = $individual;
        $new->updateGenes($point, $individual->getGene($point) == 0 ? 1 : 0);

        return $new;
    }
}