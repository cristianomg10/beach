<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 2:12 PM
 */

namespace App\Genetic;


use App\Genetic\Operators\BinaryChromosome;
use App\Utils\Functions\ObjectiveFunctions\IObjectiveFunction;
use App\Genetic\Operators\ICrossOver;
use App\Genetic\Operators\IMutation;
use App\Genetic\Operators\ISelection;
use App\Utils\Interfaces\IOptimizer;
use App\Utils\Math;

class Genetic implements IOptimizer {

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
    private $bestFitness;
    private $optimization; // Maximization or minimization

    function __construct($populationSize, $generations, $probabilityCrossOver, $probabilityMutation,
                         IObjectiveFunction $objFunction, ISelection $selection, ICrossOver $crossOver,
                         IMutation $mutation, $elitism = 1, $optimization = 'MAX')
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
        $this->optimization = $optimization;

        switch ($this->optimization){
            case "MIN":
                $this->bestFitness = INF;
                break;
            case "MAX":
                $this->bestFitness = 0;
                break;
        }
    }

    private function compare($current, $best){
        switch ($this->optimization){
            case 'MAX': {
                if ($current > $best) return true;
                break;
            }
            case 'MIN': {
                if ($current < $best) return true;
                break;
            }
        }

        return false;
    }

    function run(){
        $population = [];

        for ($i = 0; $i < $this->populationSize; ++$i){
            $c = new BinaryChromosome();
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

                $index = ($this->optimization == 'MAX' ?
                    $this->populationSize - 1: // get the biggest value
                    0   // get the smallest value
                );

                $bestIndividual = $population[$index];
                $bestFitness = $fitnesses[$index];

                if ($this->elitism){
                    if ($this->compare($bestFitness, $this->bestFitness)){
                    //if ($bestFitness > $this->bestFitness){
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
        $this->bestIndiv = $bestIndividual;
        $this->bestFitness = $bestFitness;

        return ["individual" => $bestIndividual, "fitness" => $bestFitness];
    }

    /**
     * @return int
     */
    public function getBestFitness()
    {
        return $this->bestFitness;
    }

    /**
     * @return mixed
     */
    public function getBest()
    {
        return $this->bestIndiv;
    }
}