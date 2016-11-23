<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/22/16
 * Time: 4:55 PM
 */

namespace App\ParticleSwarmOptimization;


use MathPHP\Probability\Distribution\Continuous\ParetoTest;

class Particle
{
    private $location;
    private $velocity = [];
    private $fitness = INF;
    private $best = null;

    function __construct($location, $velocity)
    {
        $this->location = $location;
        $this->velocity = $velocity;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return array
     */
    public function getVelocity(): array
    {
        return $this->velocity;
    }

    /**
     * @param $velocity
     */
    public function setVelocity($velocity)
    {
        $this->velocity = $velocity;
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
    public function setFitness($fitness, $recursive = null)
    {
        $this->fitness = $fitness;

        if (is_null($recursive)) {
            if (is_null($this->getBest())) {
                $this->best = new Particle($this->location, $this->velocity);
                $this->best->setFitness($fitness, 1);
            } else if ($fitness < $this->getBest()->getFitness()) {
                $this->best->setLocation($this->location);
                $this->best->setFitness($fitness, 1);
            }
        }

    }

    /**
     * @return mixed
     */
    public function getBest()
    {
        return $this->best;
    }

    /**
     * @param mixed $best
     */
    public function setBest($best)
    {
        $this->best = $best;
    }

}