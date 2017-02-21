<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 2/18/17
 * Time: 12:53 PM
 */

namespace App\Optimizers\Genetic\Operators\Mutators;


use App\Optimizers\Genetic\Operators\Elements\FloatChromosome;
use App\Utils\Exceptions\IllegalArgumentException;
use App\Utils\Math;

class BiasedMutation implements IMutation
{

    public function mutate($individual)
    {
        if (!is_a($individual, FloatChromosome::class)){
            throw new IllegalArgumentException("Individual is not a Chromosome.");
        }

        $new = new FloatChromosome();
        for ($i = 0; $i < $individual->getLength(); ++$i){
            if (Math::getRandomValue() < $this->internalProb){
                $new->updateGenes($i, $individual->getGene($i) + Math::getRandomValue() * 2 - 1);
            } else {
                $new->updateGenes($i, $individual->getGene($i));
            }
        }

        return $new;
    }

    public function __construct($internalProb = 0.1)
    {
        $this->internalProb = $internalProb;
    }
}