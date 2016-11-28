<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 2:18 PM
 */

namespace App\Genetic\Operators;


use App\Utils\Exceptions\IllegalArgumentException;
use App\Genetic\Chromosome;

class SinglePointCrossOver implements ICrossOver
{
    private $point;
    /**
     * SinglePointCrossOver constructor. If point = 0, the the point will be random.
     * @param int $point
     */
    function __construct($point = 0){
        $this->point = $point;
    }

    public function crossOver($individual1, $individual2)
    {
        if (!is_a($individual1, Chromosome::class) || !is_a($individual2, Chromosome::class)){
            throw new IllegalArgumentException("Individuals are not Chromosomes.");
        }

        if (!$this->point)
            $point = rand(1, $individual1->getLength() - 1);

        $newIndividual1 = new Chromosome();
        $newIndividual2 = new Chromosome();

        for ($i = 0; $i < $individual1->getLength(); ++$i){
            if ($i < $point){
                $newIndividual1->updateGenes($i, $individual1->getGene($i));
                $newIndividual2->updateGenes($i, $individual2->getGene($i));
            }else{
                $newIndividual1->updateGenes($i, $individual2->getGene($i));
                $newIndividual2->updateGenes($i, $individual1->getGene($i));
            }
        }

        return [$newIndividual1, $newIndividual2];
    }
}