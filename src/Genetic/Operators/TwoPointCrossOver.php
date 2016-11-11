<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 2:36 PM
 */

namespace App\Genetic\Operators;


use App\Exceptions\IllegalArgumentException;

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
        if (
            ($this->point2 <= $this->point1 and $this->point1 != 0) ||
            ($this->point1 > count($individual1) - 1 || $this->point2 > count($individual1) - 1)
        ){
            throw new IllegalArgumentException("Second point bigger than the First point of cut.");
        }

        if (!$this->point1)
            $point1 = rand(1, round(count($individual1) / 2));

        if (!$this->point2)
            $point2 = rand($point1 + 1, count($individual1) - 1);

        $newIndividual1 = [];
        $newIndividual2 = [];

        for ($i = 0; $i < count($individual1); ++$i){
            if ($i < $point1 || $i >= $point2){
                $newIndividual1[] = $individual1[$i];
                $newIndividual2[] = $individual2[$i];
            } else {
                $newIndividual1[] = $individual2[$i];
                $newIndividual2[] = $individual1[$i];
            }
        }

        return [$newIndividual1, $newIndividual2];
    }
}