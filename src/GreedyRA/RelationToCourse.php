<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/27/16
 * Time: 10:12 PM
 */

namespace App\GreedyRA;


class RelationToCourse
{
    private $course;
    private $qtyCount;
    private $semester;

    /**
     * RelationToCourse constructor.
     * @param $courseName
     * @param $qtyCount
     * @param $semester
     */
    public function __construct(Course $course, $qtyCount='', $semester='')
    {
        $this->course = $course;
        $this->qtyCount = $qtyCount;
        $this->semester = $semester;
    }

    /**
     * @return mixed
     */
    public function getCourseName()
    {
        return $this->courseName;
    }

    /**
     * @param mixed $courseName
     */
    public function setCourseName($courseName)
    {
        $this->courseName = $courseName;
    }

    /**
     * @return mixed
     */
    public function getQtyCount()
    {
        return $this->qtyCount;
    }

    /**
     * @param mixed $qtyCount
     */
    public function setQtyCount($qtyCount)
    {
        $this->qtyCount = $qtyCount;
    }

    /**
     * @return mixed
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * @param mixed $semester
     */
    public function setSemester($semester)
    {
        $this->semester = $semester;
    }

    public function addSubjectToCourse($subject){
        $this->course->addSubject($subject);
    }

    public function getCourse(){
        return $this->course;
    }
}