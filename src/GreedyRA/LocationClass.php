<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/23/16
 * Time: 11:12 AM
 */

namespace App\GreedyRA;


class LocationClass
{
    private $capacity;
    private $name;
    private $location;

    /**
     * LocationClass constructor.
     * @param $capacity
     * @param $name
     * @param $location
     */
    public function __construct($capacity, $name, $location)
    {
        $this->capacity = $capacity;
        $this->name = $name;
        $this->location = $location;
    }


    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param mixed $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
}