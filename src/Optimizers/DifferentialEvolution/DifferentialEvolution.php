<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/17/16
 * Time: 9:58 PM
 */

namespace App\Optimizers\DifferentialEvolution;

use App\Utils\Functions\ObjectiveFunctions\IObjectiveFunction;
use App\Optimizers\DifferentialEvolution\Strategies\IStrategy;
use App\Utils\Interfaces\IOptimizer;
use App\Utils\Loggable\ILoggable;
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
    private $logger;
    private $max;
    private $min;

    function __construct(IObjectiveFunction $objectiveFunction, IStrategy $strategy,
                         $individualSize, $populationSize, $generations, $initFactor, $probCrossOver,
                         ILoggable $logger, $min, $max, $F1, $F2 = 0){
        $this->populationSize = $populationSize;
        $this->objectiveFunction = $objectiveFunction;
        $this->generations = $generations;
        $this->F1 = $F1;
        $this->F2 = $F2;
        $this->initFactor = $initFactor;
        $this->probCrossOver = $probCrossOver;
        $this->individualSize = $individualSize;
        $this->strategy = $strategy;
        $this->logger = $logger;
        $this->min = $min;
        $this->max = $max;
    }

    private function initialize(){
        for ($i = 0; $i < $this->populationSize; ++$i){
            /*$individualContent = [];
            for ($j = 0; $j < $this->individualSize; ++$j){
                $individualContent[$j] = Math::getRandomValue() * $this->initFactor;
            }*/

            $individualContent = Math::sumNumberToMatrix(Math::generateRandomVector($this->individualSize, $this->max * 2), - abs($this->min));

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


            array_multisort($fitness, $this->individuals);
            $this->logger->write("Iteration $i: Best Fitness: {$this->objectiveFunction->compute($this->individuals[0]->getData())}");
        }

        array_multisort($fitness, $this->individuals);

        $this->best = $this->individuals[0];
    }
}