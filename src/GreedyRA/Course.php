<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/27/16
 * Time: 10:48 PM
 */

namespace App\GreedyRA;


class Course
{
    private $name;
    private $relationToSubjects;

    /**
     * Course constructor.
     * @param $name
     * @param $relationToSubjects
     */
    public function __construct($name, $relationToSubjects)
    {
        $this->name = $name;
        $this->relationToSubjects = $relationToSubjects;
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
    public function getRelationToSubjects()
    {
        return $this->relationToSubjects;
    }

    /**
     * @param mixed $relationToSubjects
     */
    public function setRelationToSubjects($relationToSubjects)
    {
        $this->relationToSubjects = $relationToSubjects;
    }

    public function addSubject($subject){
        $this->relationToSubjects[] = $subject;
    }
}