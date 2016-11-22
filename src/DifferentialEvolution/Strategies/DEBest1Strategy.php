<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/22/16
 * Time: 2:31 PM
 */

namespace App\DifferentialEvolution\Strategies;


use App\DifferentialEvolution\Individual;
use App\DifferentialEvolution\ObjectiveFunctions\IObjectiveFunction;
use App\Utils\Math;

class DEBest1Strategy implements IStrategy
{

    public function calculate($individuals, $probCrossOver, IObjectiveFunction $objectiveFunction, $F1, $F2 = 0)
    {
        $fitness = [];
        $populationSize = count($individuals);
        $individualSize = count($individuals[0]->getData());

        for ($j = 0; $j < $populationSize; ++$j) {
            $fitness[$j] = $objectiveFunction->compute($individuals[$j]->getData());
        }

        array_multisort($fitness, $individuals);

        for ($j = 0; $j < $populationSize; ++$j) {
            $rBest = $individuals[0];
            $r2 = $individuals[rand(0, $populationSize - 1)];
            $r3 = $individuals[rand(0, $populationSize - 1)];

            $varInd = rand(0, $individualSize - 1);
            $trial = [];

            for ($k = 0; $k < $individualSize; ++$k) {

                if (Math::getRandomValue() <= $probCrossOver || $k == $varInd) {
                    $trial[$k] = $rBest->get($k) + $F1 * ($r2->get($k) - $r3->get($k));
                } else {
                    $trial[$k] = $individuals[$j]->get($k);
                }
            }

            if ($objectiveFunction->compute($trial) < $objectiveFunction->compute($individuals[$j]->getData())) {
                $individuals[$j] = new Individual($trial);
                $fitness[$j] = $objectiveFunction->compute($trial);
            }
        }

        return [$fitness, $individuals];
    }
}