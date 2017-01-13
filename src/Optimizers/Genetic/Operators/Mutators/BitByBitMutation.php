<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 3:33 PM
 */

namespace App\Optimizers\Genetic\Operators\Mutators;

use App\Optimizers\Genetic\Operators\Elements\BinaryChromosome;
use App\Optimizers\Genetic\Operators\Mutators\IMutation;
use App\Utils\Math;

class BitByBitMutation implements IMutation
{
    private $rate;

    function __construct($rate = 0.5){
        $this->rate = $rate;
    }

    public function mutate($individual)
    {
        if (!is_a($individual, BinaryChromosome::class)){
            throw new IllegalArgumentException("Individual is not a Chromosome.");
        }

        $new = new BinaryChromosome();
        for ($i = 0; $i < $individual->getLength(); ++$i){
            if (Math::getRandomValue() < $this->rate){
                $new->updateGenes($i, ($individual->getGene($i) == 1 ? 0 : 1));
            } else {
                $new->updateGenes($i, $individual->getGene($i));
            }
        }

        return $new;
    }
}