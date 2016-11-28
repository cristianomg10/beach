<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/28/16
 * Time: 3:03 PM
 */

namespace App\Genetic;


class GeneticTSP
{
    function __construct($populationSize, $generations, $probabilityCrossOver,
                         IObjectiveFunction $objFunction, ISelection $selection,
                         $elitism = 1, $optimization = 'MAX'){
        $this->populationSize = $populationSize;
        $this->generations = $generations;
    }
}