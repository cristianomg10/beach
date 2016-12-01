<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/28/16
 * Time: 4:30 PM
 */

namespace App\Utils\Functions\ObjectiveFunctions;


use MathPHP\LinearAlgebra\Matrix;

class TSProblemFunction implements IObjectiveFunction
{
    private $cities;

    function __construct(Matrix $cities)
    {
        $this->cities = $cities;
    }

    public function compute($individual)
    {
        $count = 0;

        for ($i = 0; $i < $individual->getLength() - 1; ++$i){
            $city1 = $individual->getGene($i);
            $city2 = $individual->getGene($i+1);
            $count += $this->cities->get($city1, $city2);
        }

        $count += $this->cities->get($individual->getGene($individual->getLength() - 1), $individual->getGene(0));

        return $count;
    }
}