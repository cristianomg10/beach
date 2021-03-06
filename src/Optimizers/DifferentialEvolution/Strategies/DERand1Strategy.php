<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/22/16
 * Time: 2:11 PM
 */

namespace App\Optimizers\DifferentialEvolution\Strategies;


use App\Optimizers\DifferentialEvolution\Individual;
use App\Utils\Functions\ObjectiveFunctions\IObjectiveFunction;
use App\Utils\Math;

class DERand1Strategy implements IStrategy
{

    public function calculate($individuals, $probCrossOver, IObjectiveFunction $objectiveFunction, $F1, $F2 = 0)
    {
        $fitness = [];
        $populationSize = count($individuals);
        $individualSize = count($individuals[0]->getData());

        for ($j = 0; $j < $populationSize; ++$j) {
            $fitness[$j] = $objectiveFunction->compute($individuals[$j]->getData());
        }

        for ($j = 0; $j < $populationSize; ++$j) {
            $r1 = $individuals[rand(0, $populationSize - 1)];
            $r2 = $individuals[rand(0, $populationSize - 1)];
            $r3 = $individuals[rand(0, $populationSize - 1)];

            $varInd = rand(0, $individualSize - 1);
            $trial = [];

            for ($k = 0; $k < $individualSize; ++$k) {

                if (Math::getRandomValue() <= $probCrossOver || $k == $varInd) {
                    $trial[$k] = $r1->get($k) + $F1 * ($r2->get($k) - $r3->get($k));
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