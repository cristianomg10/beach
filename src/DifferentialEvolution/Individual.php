<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/17/16
 * Time: 9:53 PM
 */

namespace App\DifferentialEvolution;


use MathPHP\LinearAlgebra\Matrix;

class Individual
{
    private $data;

    function __construct($data){
        $this->data = $data;
    }

    public function getData(){
        return $this->data;
    }

    public function get($index){
        return $this->data[$index];
    }

    public function set($index, $value){
        $this->data[$index] = $value;
    }

    function __toString(): string
    {
        return "Individual: " . new Matrix([$this->data]) . "\n";
    }
}