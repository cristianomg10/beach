<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/28/16
 * Time: 3:03 PM
 */

namespace App\Genetic;


use App\Genetic\Operators\IMutation;
use App\Genetic\Operators\PermutationChromosome;
use App\Utils\Functions\ObjectiveFunctions\IObjectiveFunction;
use App\Utils\Interfaces\IOptimizer;
use App\Utils\Math;

class GeneticTSP implements IOptimizer
{
    private $optimization;
    private $populationSize;
    private $objectiveFunction;
    private $selection;
    private $elitism;
    private $generations;
    private $mutation;
    private $probabilityMutation;
    private $population;
    private $bestFitness;
    private $bestIndiv;
    private $individualSize;

    /**
     * GeneticTSP constructor.
     * @param $individualSize
     * @param $populationSize
     * @param $generations
     * @param $probabilityMutation
     * @param IObjectiveFunction $objFunction
     * @param IMutation $mutation
     * @param int $elitism
     * @param string $optimization
     * @internal param ISelection $selection
     */
    function __construct($individualSize, $populationSize, $generations, $probabilityMutation,
                            IObjectiveFunction $objFunction,
                            IMutation $mutation, $elitism = 1, $optimization = 'MIN'){
        $this->individualSize = $individualSize;
        $this->populationSize = $populationSize;
        $this->generations = $generations;
        $this->objectiveFunction = $objFunction;
        $this->elitism = $elitism;
        $this->optimization = $optimization;
        $this->mutation = $mutation;
        $this->probabilityMutation = $probabilityMutation;

        switch ($this->optimization){
            case "MIN":
                $this->bestFitness = INF;
                break;
            case "MAX":
                $this->bestFitness = 0;
                break;
        }
    }

    private function initialize(){

        for ($i = 0; $i < $this->populationSize; ++$i){
            $c = new PermutationChromosome();
            $c->initialize($this->individualSize);
            $this->population[] = $c;
            $this->fitnesses[] = $this->objectiveFunction->compute($c);
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

    public function getBest()
    {
        return $this->bestIndiv;
    }

    public function run()
    {
        $this->initialize();

        for ($i = 0; $i < $this->generations; ++$i){
            for ($j = 0; $j < $this->populationSize; ++$j) {

                if (Math::getRandomValue() < $this->probabilityMutation) {
                    $this->population[$j] = $this->mutation->mutate($this->population[$j]);
                }

                array_multisort($this->fitnesses, $this->population);

                $index = ($this->optimization == 'MAX' ?
                    $this->populationSize - 1 : // get the biggest value
                    0   // get the smallest value
                );


                /*$bestIndividual = $this->population[$index];
                $bestFitness = $this->fitnesses[$index];
                    */
                if ($this->elitism) {
                    if ($this->compare($this->fitnesses[$index], $this->bestFitness)) {
                        //if ($bestFitness > $this->bestFitness){
                        $this->bestFitness = $this->fitnesses[$index];
                        $this->bestIndiv = $this->population[$index];
                    } else {
                        $this->population[$index] = $this->bestIndiv;
                        $this->fitnesses[$index] = $this->bestFitness;
                    }
                }
            }
        }

        return ["individual" => $this->bestIndiv, "fitness" => $this->bestFitness];
    }
}