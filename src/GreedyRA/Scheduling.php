<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/23/16
 * Time: 11:19 AM
 */

namespace App\GreedyRA;


class Scheduling
{
    private $schedule;
    private $possibleSchedules;
    private $arrayOfClasses;
    private $daysOfWeek;

    /**
     * Scheduling constructor.
     * @param $possibleSchedules
     * @param $arrayOfClasses
     * @param $daysOfWeek
     */
    public function __construct($possibleSchedules, $arrayOfClasses, $daysOfWeek)
    {
        $this->possibleSchedules = $possibleSchedules;
        $this->arrayOfClasses = $arrayOfClasses;
        $this->daysOfWeek = $daysOfWeek;

        $this->newEmptySchedule();
    }

    private function newEmptySchedule(){
        $schedule = [];
        foreach ($this->daysOfWeek as $day){
            foreach ($this->possibleSchedules as $time){
                foreach ($this->arrayOfClasses as $class){
                    $schedule[$day][$time][$class->getName()] = null;
                }
            }
        }

        $this->schedule = $schedule;
    }

    private function fixArrayOfSubjects($arrayOfSubjects){
        $newArrayOfSubjects = [];

        foreach ($arrayOfSubjects as $a){
            for ($i = 0; $i < $a->getClassesAWeek(); ++$i)
                $newArrayOfSubjects[] = $a;
        }

        return $newArrayOfSubjects;
    }

    public function generateSchedule($arrayOfSubjects){

        $uniqueArrayOfSubjects = [];
        foreach ($arrayOfSubjects as $as){
            $uniqueArrayOfSubjects[] = $as->getName();
        }

        $arrayOfSubjects = $this->fixArrayOfSubjects($arrayOfSubjects);

        $classSize = [];
        foreach ($arrayOfSubjects as $s){
            $classSize[] = $s->getStudentsEnrolled();
        }

        $capacities = [];
        foreach ($this->arrayOfClasses as $c){
            $capacities[] = $c->getCapacity();
        }

        array_multisort($classSize, SORT_DESC, $arrayOfSubjects);
        array_multisort($capacities, SORT_DESC, $this->arrayOfClasses);

        $v = count($arrayOfSubjects);
        $taken = array_fill(0, $v, '');
        $taken = $this->getSubjectNamesAlreadyInTheSchedule($taken, $arrayOfSubjects);

        //check classes
        for ($i = 0; $i < $v; ++$i) {
            //verifica salas
            for ($j = 0; $j < count($this->arrayOfClasses); ++$j) {
                foreach ($this->daysOfWeek as $d){
                    //verifica horarios
                    $uniqueSubjects = $uniqueArrayOfSubjects;
                    if ($arrayOfSubjects[$i]->getStudentsEnrolled() <= $this->arrayOfClasses[$j]->getCapacity()) {
                        foreach ($this->possibleSchedules as $h) {
                            if ($this->schedule[$d][$h][$this->arrayOfClasses[$j]->getName()] == null
                                && $taken[$i] == ''
                                && in_array($arrayOfSubjects[$i]->getName(), $uniqueSubjects)
                                && !$this->isSameCourseAndSemester($arrayOfSubjects[$i], $this->schedule[$d][$h])
                            ) {
                                $this->schedule[$d][$h][$this->arrayOfClasses[$j]->getName()] = $arrayOfSubjects[$i];
                                //unset($turmas[$i]);
                                //unset($alunos[$i]);
                                $index = array_search($arrayOfSubjects[$i]->getName(), $uniqueSubjects);
                                unset($uniqueSubjects[$index]);
                                $taken[$i] = 'taken';
                                if ($i < $v - 1) ++$i;
                                continue;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    private function isSameCourseAndSemester(Subject $subject, $subjectOfDay){
        foreach ($subjectOfDay as $sd){
            for ($i = 0; $i < count($subject->getCourseRelation()); ++$i){
                $scr = $subject->getCourseRelation();
                if ($sd == null) {
                    continue;
                }else{
                    //echo "hey";
                }

                for ($j = 0; $j < count($sd->getCourseRelation()); ++$j){
                    $sdcr = $sd->getCourseRelation();
                    if ($scr[$i]->getCourse()->getName() == $sdcr[$j]->getCourse()->getName()
                        && $scr[$i]->getSemester() == $sdcr[$j]->getSemester()){
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function getSubjectNamesAlreadyInTheSchedule($taken, $arrayOfSubjects){
        $subjects = [];

        foreach ($this->schedule as $weekDays => $times){
            foreach ($times as $time => $classes){
                foreach ($classes as $c){
                    if (!in_array($c, $subjects) && $c != null){
                        $subjects[] = $c->getName();
                    }
                }
            }
        }

        //return $subjects;

        for ($i = 0; $i < count($arrayOfSubjects); ++$i){
            $a = $arrayOfSubjects[$i]->getName();
            if (in_array($a, $subjects)){
                $taken[$i] = 'taken';
            }
        }

        return $taken;
    }

    public function __toString() : string
    {
        $text = "\t\t";

        foreach ($this->schedule as $k => $v){
            $text .= "$k\t\t\t\t";
        }
        $text .= "\n";

        foreach ($this->possibleSchedules as $h){
            $text .= "$h\t";
            foreach ($this->arrayOfClasses as $classes){
                foreach ($this->schedule as $k => $v){
                    $className = $classes->getName();
                    $subjects = $this->schedule[$k][$h][$className];
                    if ($subjects != null) $text .= "{$subjects}[$className]\t";

                }
                $text .= "\n\t\t";
            }
            $text .= "\n";
        }
        $text .= "\n";

        return $text;
    }
}