<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/23/16
 * Time: 11:11 AM
 */

namespace App\GreedyRA;


class Subject
{
    private $studentsEnrolled = 0;
    private $name;
    private $classesAWeek;
    private $belongsToCourse = [];

    /**
     * Subject constructor.
     * @param $name
     * @param $studentsEnrolled
     * @param $classesAWeek
     */
    public function __construct($name, $belongsToCourse, $classesAWeek=2)
    {
        $this->name = $name;
        $this->classesAWeek = $classesAWeek;

        if (!is_array($belongsToCourse)){
            $this->belongsToCourse = [$belongsToCourse];
            $belongsToCourse->addSubjectToCourse($this);
            $this->studentsEnrolled = $belongsToCourse->getQtyCount();
        } else {
            $this->belongsToCourse = $belongsToCourse;

            foreach($belongsToCourse as $c){
                $c->addSubjectToCourse($this);
                $this->studentsEnrolled += $c->getQtyCount();
            }
        }


    }

    /**
     * @return mixed
     */
    public function getStudentsEnrolled()
    {
        return $this->studentsEnrolled;
    }

    /**
     * @param mixed $studentsEnrolled
     */
    public function setStudentsEnrolled($studentsEnrolled)
    {
        $this->studentsEnrolled = $studentsEnrolled;
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
     * @return int
     */
    public function getClassesAWeek(): int
    {
        return $this->classesAWeek;
    }

    public function addCourseRelation($course){
        $this->belongsToCourse[] = $course;
        $this->studentsEnrolled += $course->getQtyCount();

    }

    public function getCourseRelation(){
        return $this->belongsToCourse;
    }

    public function __toString() : string
    {
        return "{$this->name}";
    }
}