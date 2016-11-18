<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/17/16
 * Time: 9:58 PM
 */

namespace App\DifferentialEvolution;

use App\DifferentialEvolution\ObjectiveFunctions\IObjectiveFunction;
use App\Utils\Math;

class DifferentialEvolution
{
    private $objectiveFunction;
    private $populationSize;
    private $generations;
    private $F;
    private $initFactor;
    private $best;
    private $individuals;
    private $individualSize;
    private $probCrossOver;

    function __construct(IObjectiveFunction $objectiveFunction, $individualSize, $populationSize, $generations, $F, $initFactor, $probCrossOver){
        $this->populationSize = $populationSize;
        $this->objectiveFunction = $objectiveFunction;
        $this->generations = $generations;
        $this->F = $F;
        $this->initFactor = $initFactor;
        $this->probCrossOver = $probCrossOver;
        $this->individualSize = $individualSize;
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

    function getBest(){
        return $this->best;
    }

    function run(){
        $this->initialize();

        for ($i = 0; $i < $this->generations; ++$i){

            $fitness = [];
            for ($j = 0; $j < $this->populationSize; ++$j) {
                $fitness[$j] = $this->objectiveFunction->compute($this->individuals[$j]->getData());
            }

            for ($j = 0; $j < $this->populationSize; ++$j) {
                $r1 = $this->individuals[rand(0, $this->populationSize - 1)];
                $r2 = $this->individuals[rand(0, $this->populationSize - 1)];
                $r3 = $this->individuals[rand(0, $this->populationSize - 1)];

                $varInd = rand(0, $this->individualSize - 1);
                $trial = [];

                for ($k = 0; $k < $this->individualSize; ++$k) {

                    if (Math::getRandomValue() <= $this->probCrossOver || $k == $varInd) {
                        $trial[$k] = $r1->get($k) + $this->F * ($r2->get($k) - $r3->get($k));
                    } else {
                        $trial[$k] = $this->individuals[$j]->get($k);
                    }
                }

                if ($this->objectiveFunction->compute($trial) < $this->objectiveFunction->compute($this->individuals[$j]->getData())) {
                    $this->individuals[$j] = new Individual($trial);
                    $fitness[$j] = $this->objectiveFunction->compute($trial);
                }
            }
        }

        array_multisort($fitness, $this->individuals);

        $this->best = $this->individuals[0];
    }
}