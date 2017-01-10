<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/17/16
 * Time: 9:58 PM
 */

namespace App\DifferentialEvolution;

use App\Utils\Functions\ObjectiveFunctions\IObjectiveFunction;
use App\DifferentialEvolution\Strategies\IStrategy;
use App\Utils\Interfaces\IOptimizer;
use App\Utils\Math;

class DifferentialEvolution implements IOptimizer
{
    private $objectiveFunction;
    private $populationSize;
    private $generations;
    private $F1;
    private $F2;
    private $initFactor;
    private $strategy;
    private $best;
    private $individuals;
    private $individualSize;
    private $probCrossOver;

    function __construct(IObjectiveFunction $objectiveFunction, IStrategy $strategy, $individualSize, $populationSize, $generations, $initFactor, $probCrossOver, $F1, $F2 = 0){
        $this->populationSize = $populationSize;
        $this->objectiveFunction = $objectiveFunction;
        $this->generations = $generations;
        $this->F1 = $F1;
        $this->F2 = $F2;
        $this->initFactor = $initFactor;
        $this->probCrossOver = $probCrossOver;
        $this->individualSize = $individualSize;
        $this->strategy = $strategy;
    }

    private function initialize(){
        for ($i = 0; $i < $this->populationSize; ++$i){
            $individualContent = [];
            for ($j = 0; $j < $this->individualSize; ++$j){
                $individualContent[$j] = Math::getRandomValue() * $this->initFactor;
            }

            $this->individuals[$i] = new Individual($individualContent);
        }
    }

    function getBest() : array{
        return $this->best->getData();
    }

    function run(){
        $this->initialize();

        for ($i = 0; $i < $this->generations; ++$i){

            list($fitness, $this->individuals) = $this->strategy->calculate(
                $this->individuals,
                $this->probCrossOver,
                $this->objectiveFunction,
                $this->F1,
                $this->F2
            );
        }

        array_multisort($fitness, $this->individuals);

        $this->best = $this->individuals[0];
    }
}