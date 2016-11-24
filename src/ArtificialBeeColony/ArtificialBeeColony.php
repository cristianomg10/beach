<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/24/16
 * Time: 4:14 PM
 */

namespace App\ArtificialBeeColony;


use App\ArtificialBeeColony\Operators\ISelection;
use App\Functions\ObjectiveFunctions\IObjectiveFunction;
use App\Loggable\ILoggable;
use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;

class ArtificialBeeColony
{
    private $nBees;
    private $nIterations;
    /*private $c1;
    private $c2;*/
    private $nDimensions;
    private $objectiveFunction;
    private $bees;
    private $bestFitness = INF;
    private $bestBee;
    private $fitness;
    private $min;
    private $max;
    private $loggable;
    private $nOnlookers;
    private $a; // acceleration coefficient
    private $selection;

    function __construct($nBees, $nOnlookers, $nIterations, $nDimensions, IObjectiveFunction $objectiveFunction, $min, $max, ILoggable $loggable, ISelection $selection, $a = 1)
    {
        $this->nBees = $nBees;
        $this->nIterations = $nIterations;
        $this->nDimensions = $nDimensions;
        $this->objectiveFunction = $objectiveFunction;
        $this->min = $min;
        $this->max = $max;
        $this->loggable = $loggable;
        $this->nOnlookers = $nOnlookers;
        $this->a = $a;
        $this->selection = $selection;
    }

    private function initialize(){
        for ($i = 0; $i < $this->nBees; ++$i){
            $bee = new Bee(
                Math::sumNumberToMatrix(Math::generateRandomVector($this->nDimensions, $this->max * 2), - abs($this->min))
            );

            $bee->setFitness($this->objectiveFunction->compute($bee->getPosition()));
            $this->bees[$i] = $bee;

            if ($this->bees[$i]->getFitness() < $this->bestFitness){
                $this->bestBee = $this->bees[$i];
                $this->bestFitness = $this->bees[$i]->getFitness();
            }

            $this->fitness[$i] = $this->bees[$i]->getFitness();
        }
    }

    private function updateFitnesses(){
        for ($i = 0; $i < $this->nBees; ++$i){
            $this->bees[$i]->setFitness($this->objectiveFunction->compute($this->bees[$i]->getPosition()));
            $this->fitness[$i] = $this->bees[$i]->getFitness();
        }
    }

    public function run()
    {
        $this->initialize();
        $abandonment = [];
        $abandonmentLimit = 0.6 * $this->nBees * $this->nDimensions;

        for ($i = 0; $i < $this->nIterations; ++$i) {
            // employed bees
            for ($j = 0; $j < $this->nBees; ++$j) {

                $k = $j;
                //select random index
                while ($k == $j) $k = round(Math::getRandomValue($this->nBees - 1));

                $part1 = Math::generateRandomVector($this->nDimensions, 2);
                $part2 = Math::sumNumberToMatrix($part1, -1);
                $phi = Math::multiplyNumberToMatrix($part2, $this->a);

                $currentBeePosition = new Matrix([$this->bees[$j]->getPosition()]);
                $kPosition = new Matrix([$this->bees[$k]->getPosition()]);

                $diff = $currentBeePosition->subtract($kPosition);

                $partPhi = (new Matrix([$phi]))->hadamardProduct($diff);
                $position = $currentBeePosition->add($partPhi)->getRow(0);
                $newBee = new Bee($position);
                $newBee->setFitness($this->objectiveFunction->compute($newBee->getPosition()));

                if ($newBee->getFitness() < $this->bees[$j]->getFitness()) {
                    $this->bees[$j] = $newBee;
                } else {
                    if (is_null($abandonment[$j])) {
                        $abandonment[$j] = 1;
                    } else {
                        ++$abandonment[$j];
                    }
                }
            }

            //onlooker bees
            $this->updateFitnesses();

            /*$f = [];
            $prob = [];
            $mean = array_sum($this->fitness) / $this->nBees;
            for ($j = 0; $j < $this->nBees; ++$j){
                $f[$j] = exp(- $this->bees[$j]->getFitness() / $mean);
                $prob[$j] = $f / array_sum($f);
            }*/

            for ($j = 0; $j < $this->nBees; ++$j) {

                //select random index
                $m = $this->selection->select($this->fitness);
                $k = $m;
                while ($k == $m) $k = round(Math::getRandomValue($this->nBees - 1));

                $part1 = Math::generateRandomVector($this->nDimensions, 2);
                $part2 = Math::sumNumberToMatrix($part1, -1);
                $phi = Math::multiplyNumberToMatrix($part2, $this->a);

                $onLookerBeePosition = new Matrix([$this->bees[$m]->getPosition()]);
                $kPosition = new Matrix([$this->bees[$k]->getPosition()]);

                $diff = $onLookerBeePosition->subtract($kPosition);

                $partPhi = (new Matrix([$phi]))->hadamardProduct($diff);
                $position = $onLookerBeePosition->add($partPhi)->getRow(0);
                $newBee = new Bee($position);
                $newBee->setFitness($this->objectiveFunction->compute($newBee->getPosition()));

                if ($newBee->getFitness() < $this->bees[$m]->getFitness()) {
                    $this->bees[$m] = $newBee;
                } else {
                    if (is_null($abandonment[$m])) {
                        $abandonment[$m] = 1;
                    } else {
                        ++$abandonment[$m];
                    }
                }
            }

            //scout bees
            for ($j = 0; $j < $this->nBees; ++$j) {
                if ($abandonment[$j] >= $abandonmentLimit) {
                    $this->bees[$j]->setPosition(Math::sumNumberToMatrix(Math::generateRandomVector($this->nDimensions, $this->max * 2), -abs($this->min)));
                    $this->bees[$j]->setFitness($this->objectiveFunction($this->bees[$j]->getFitness()));
                    $this->fitness[$j] = $this->bees[$j]->getFitness();
                }
                $abandonment[$j] = 0;
            }

            // update best solution;
            for ($j = 0; $j < $this->nBees; ++$j) {
                if ($this->bees[$j]->getFitness() <= $this->bestFitness) {
                    $this->bestFitness = $this->bees[$j]->getFitness();
                    $this->bestBee = $this->bees[$j];
                }
            }

            $this->loggable->write("Iteration $i: Best Fitness: {$this->bestFitness}");
        }
    }

    function getBestBee(){
        return $this->bestBee;
    }
}