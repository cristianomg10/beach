<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 1/12/17
 * Time: 3:13 PM
 */

namespace App\KNN;


use App\Utils\Interfaces\IClassifier;
use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;

class KNN implements IClassifier
{
    private $input;
    private $expectedOutput;
    private $k;
    private $distanceMatrix;

    public function __construct($k = 3)
    {
        $this->k = $k;
    }

    public function setInput(Matrix $input)
    {
        $this->input = $input;
    }

    public function setExpectedOutput(Matrix $expectedOutput)
    {
        $this->expectedOutput = $expectedOutput;
    }

    public function learn()
    {
        /*$m = Math::generateRandomMatrix($this->input->getN(), $this->input->getN(), 0);
        $m = Math::sumNumberToMatrix($m, 9999);

        for ($i = 0; $i < $this->input->getN(); ++$i){
            for ($j = 0; $j < $this->input->getN(); ++$j){
                if ($i == $j) continue;

                $distance = 0;
                for ($k = 0; $k < $this->input->getM(); ++$k){
                    $distance += pow($this->input->get($k, $i) - $this->input->get($k, $j), 2);
                }

                $distance = sqrt($distance);
                $m[$i][$j] = $distance;
                $m[$j][$i] = $distance;
            }
        }

        $this->distanceMatrix = new Matrix($m);*/
    }

    public function classify(Matrix $input)
    {
        $m = Math::generateRandomMatrix($input->getN(), $this->input->getN(), 0);
        $m = Math::sumNumberToMatrix($m, 9999);

        for ($i = 0; $i < $input->getN(); ++$i){
            for ($j = 0; $j < $this->input->getN(); ++$j){
                //if ($i == $j) continue;

                $distance = 0;
                for ($k = 0; $k < $this->input->getM(); ++$k){
                    $distance += pow($this->input->get($k, $i) - $this->input->get($k, $j), 2);
                }

                $distance = sqrt($distance);
                $m[$i][$j] = $distance;
            }
        }

        $classification = [];
        for ($i = 0; $i < count($m); ++$i){
            $nearestDist  = [];
            $nearestIndex = [];
            $nearestClass = [];

            for ($j = 0; $j < $this->input->getN(); ++$j)
            {
                $nearestClass[] = $this->expectedOutput->get(0, $j);
                $nearestIndex[] = $j;
                $nearestDist[]  = $m[$i][$j];
            }
            array_multisort($nearestDist, SORT_ASC, $nearestClass, $nearestIndex);

            $nearestClass = array_slice($nearestClass, 0, $this->k);
            #$nearestIndex = array_slice($nearestIndex, 0, $this->k);
            #$nearestDist  = array_slice($nearestDist , 0, $this->k);

            $classes = array_count_values($nearestClass);
            arsort($classes);
            $k = array_keys($classes);

            $classification[] = $k[0];
        }

        return new Matrix([$classification]);
    }
}