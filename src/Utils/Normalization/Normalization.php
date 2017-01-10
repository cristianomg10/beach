<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 1/6/17
 * Time: 11:26 AM
 */

namespace App\Utils\Normalization;


use MathPHP\LinearAlgebra\Matrix;

class Normalization implements INormalization
{
    private $biggestValue;
    private $smallestValue;
    private $max;
    private $min;
    private $index;
    private $data;

    public function __construct(Matrix $data, $min = 0, $max = 1)
    {
        $this->data = $data;
        $this->min = $min;
        $this->max = $max;
    }

    public function compute($number)
    {
        return $this->max * ($number - $this->smallestValue)/($this->biggestValue - $this->smallestValue) + $this->min;
    }

    public function setIndex($index)
    {
        $this->index = $index;
        $data = $this->data->getMatrix();
        $this->biggestValue = -1000000;
        $this->smallestValue = 1000000;

        for ($i = 0; $i < count($data); ++$i){
            if ($data[$i][$index] > $this->biggestValue){
                $this->biggestValue = $data[$i][$index];
            }

            if ($data[$i][$index] < $this->smallestValue){
                $this->smallestValue = $data[$i][$index];
            }
        }
    }
}