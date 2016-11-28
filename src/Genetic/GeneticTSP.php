<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/28/16
 * Time: 3:03 PM
 */

namespace App\Genetic;


use App\Genetic\Operators\IMutation;
use App\Utils\Interfaces\IOptimizer;

class GeneticTSP implements IOptimizer
{
    private $optimization;
    private $populationSize;
    private $objectiveFunction;
    private $selection;
    private $elitism;
    private $generations;
    private $mutation;

    /**
     * GeneticTSP constructor.
     * @param $populationSize
     * @param $generations
     * @param $probabilityMutation
     * @param IObjectiveFunction $objFunction
     * @param ISelection $selection
     * @param IMutation $mutation
     * @param int $elitism
     * @param string $optimization
     * @internal param $probabilityCrossOver
     */
    function __construct($populationSize, $generations, $probabilityMutation,
                            IObjectiveFunction $objFunction, ISelection $selection,
                            IMutation $mutation, $elitism = 1, $optimization = 'MIN'){
        $this->populationSize = $populationSize;
        $this->generations = $generations;
        $this->objectiveFunction = $objFunction;
        $this->selection = $selection;
        $this->elitism = $elitism;
        $this->optimization = $optimization;
        $this->mutation = $mutation;
    }

    public function getBest()
    {

    }

    public function run()
    {
        for ($i = 0; $i < $this->generations; ++$i){
            for ($j = 0; $j < $this->populationSize; ++$j){

            }
        }
    }
}