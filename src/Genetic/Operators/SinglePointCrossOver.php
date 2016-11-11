<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 2:18 PM
 */

namespace App\Genetic\Operators;


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
        if (!$this->point)
            $point = rand(1, count($individual1) - 1);

        $newIndividual1 = [];
        $newIndividual2 = [];

        for ($i = 0; $i < count($individual1); ++$i){
            if ($i < $point){
                $newIndividual1[] = $individual1[$i];
                $newIndividual2[] = $individual2[$i];
            }else{
                $newIndividual1[] = $individual2[$i];
                $newIndividual2[] = $individual1[$i];
            }
        }

        return [$newIndividual1, $newIndividual2];
    }
}