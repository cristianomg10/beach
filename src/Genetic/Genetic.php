<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 2:12 PM
 */

namespace App\Genetic;


use App\Genetic\ObjectiveFunctions\IObjectiveFunction;
use App\Genetic\Operators\ICrossOver;
use App\Genetic\Operators\IMutation;
use App\Genetic\Operators\ISelection;
use App\Utils\Math;

class Genetic {

    private $populationSize;
    private $population;
    private $generations;
    private $probabilityCrossOver;
    private $probabilityMutation;
    private $objFunction;
    private $selection;
    private $crossOver;
    private $mutation;
    private $elitism;
    private $bestIndiv;
    private $bestFitness = 0;

    function __construct($populationSize, $generations, $probabilityCrossOver, $probabilityMutation,
                         IObjectiveFunction $objFunction, ISelection $selection, ICrossOver $crossOver,
                         IMutation $mutation, $elitism = 1)
    {
        $this->populationSize = $populationSize;
        $this->probabilityCrossOver = $probabilityCrossOver;
        $this->probabilityMutation = $probabilityMutation;
        $this->objFunction = $objFunction;
        $this->selection = $selection;
        $this->crossOver = $crossOver;
        $this->mutation = $mutation;
        $this->generations = $generations;
        $this->elitism = $elitism;
    }

    function run(){
        $population = [];

        for ($i = 0; $i < $this->populationSize; ++$i){
            $c = new Chromosome();
            $c->initialize(8);
            $population[] = $c;
        }

        $generation = 0;

        while ($generation < $this->generations){
            for ($i = 0; $i < $this->populationSize; ++$i){

                $fitnesses = [];
                for ($j = 0; $j < $this->populationSize; ++$j){
                    $fitnesses[$j] = $this->objFunction->compute($population[$j]);
                }

                array_multisort($fitnesses, $population);

                if (Math::getRandomValue() < $this->probabilityCrossOver){
                    $index1 = $this->selection->select($fitnesses);
                    $index2 = $this->selection->select($fitnesses);
                    $individual1 = $population[$index1];
                    $individual2 = $population[$index2];

                    $newIndivs = $this->crossOver->crossOver($individual1, $individual2);
                    $population[$index1] = $newIndivs[0];
                    $population[$index2] = $newIndivs[1];
                }

                if (Math::getRandomValue() < $this->probabilityMutation){
                    $newIndiv = $this->mutation->mutate($population[$i]);
                    $population[$i] = $newIndiv;
                }

                $fitnesses = [];
                for ($j = 0; $j < $this->populationSize; ++$j){
                    $fitnesses[$j] = $this->objFunction->compute($population[$j]);
                }

                array_multisort($fitnesses, $population);

                $bestIndividual = $population[$this->populationSize - 1];
                $bestFitness = $fitnesses[$this->populationSize - 1];

                if ($this->elitism){
                    if ($bestFitness > $this->bestFitness){
                        $this->bestFitness = $bestFitness;
                        $this->bestIndiv = $bestIndividual;
                    } else {
                        $bestIndividual = $this->bestIndiv;
                        $bestFitness = $this->bestFitness;
                    }
                }
            }

            ++$generation;
        }

        $this->population = $population;

        return ["individual" => $bestIndividual, "fitness" => $bestFitness];
    }
}