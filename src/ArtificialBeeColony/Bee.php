<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/24/16
 * Time: 4:17 PM
 */

namespace App\ArtificialBeeColony;


use MathPHP\LinearAlgebra\Matrix;

class Bee
{
    private $position;
    private $fitness;

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getFitness()
    {
        return $this->fitness;
    }

    /**
     * @param mixed $fitness
     */
    public function setFitness($fitness)
    {
        $this->fitness = $fitness;
    }

    function __construct($position)
    {
        $this->position = $position;
    }

    function __toString(): string
    {
        return "Bee { Position: " . new Matrix([$this->position]) . ", Fitness: {$this->fitness}}\n";
    }
}