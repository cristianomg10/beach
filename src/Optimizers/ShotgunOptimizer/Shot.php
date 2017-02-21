<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/8/16
 * Time: 2:57 PM
 */

namespace App\Optimizers\ShotgunOptimizer;


class Shot
{
    private $position;
    private $cost;

    function __construct($position)
    {
        $this->position = $position;
    }

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
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

}