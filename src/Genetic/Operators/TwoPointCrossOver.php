<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 2:36 PM
 */

namespace App\Genetic\Operators;


use App\Utils\Exceptions\IllegalArgumentException;
use App\Genetic\BinaryChromosome;

class TwoPointCrossOver implements ICrossOver
{
    private $point1;
    private $point2;

    function __construct($point1 = 0, $point2 = 0)
    {
        $this->point1 = $point1;
        $this->point2 = $point2;
    }

    public function crossOver($individual1, $individual2)
    {

        if (!is_a($individual1, BinaryChromosome::class) || !is_a($individual2, BinaryChromosome::class)){
            throw new IllegalArgumentException("Individuals are not Chromosomes.");
        }

        if (
            ($this->point2 <= $this->point1 and $this->point1 != 0) ||
            ($this->point1 > $individual1->getLength() - 1 || $this->point2 > $individual1->getLength() - 1)
        ){
            throw new IllegalArgumentException("Second point bigger than the First point of cut.");
        }

        if (!$this->point1)
            $point1 = rand(1, round($individual1->getLength() / 2));

        if (!$this->point2)
            $point2 = rand($point1 + 1, $individual1->getLength() - 1);

        $newIndividual1 = new BinaryChromosome();
        $newIndividual2 = new BinaryChromosome();

        for ($i = 0; $i < $individual1->getLength(); ++$i){
            if ($i < $point1 || $i >= $point2){
                $newIndividual1->updateGenes($i, $individual1->getGene($i));
                $newIndividual2->updateGenes($i, $individual2->getGene($i));
            } else {
                $newIndividual1->updateGenes($i, $individual2->getGene($i));
                $newIndividual2->updateGenes($i, $individual1->getGene($i));;
            }
        }

        return [$newIndividual1, $newIndividual2];
    }
}