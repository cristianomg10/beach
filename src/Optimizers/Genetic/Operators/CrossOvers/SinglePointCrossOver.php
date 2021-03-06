<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 2:18 PM
 */

namespace App\Optimizers\Genetic\Operators\CrossOvers;


use App\Optimizers\Genetic\Operators\Elements\BinaryChromosome;
use App\Optimizers\Genetic\Operators\Elements\FloatChromosome;
use App\Utils\Exceptions\IllegalArgumentException;

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
        if (!is_a($individual1, BinaryChromosome::class) && !is_a($individual2, BinaryChromosome::class)
        && !is_a($individual1, FloatChromosome::class) && !is_a($individual2, FloatChromosome::class)){
            throw new IllegalArgumentException("Individuals are not Chromosomes.");
        }

        if (!$this->point)
            $point = rand(1, $individual1->getLength() - 1);

        $class = get_class($individual1);
        $newIndividual1 = new $class();
        $newIndividual2 = new $class();

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