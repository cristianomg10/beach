<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/24/16
 * Time: 4:14 PM
 */

namespace App\ArtificialBeeColony;


use App\ArtificialBeeColony\Operators\ISelection;
use App\Utils\Functions\ObjectiveFunctions\IObjectiveFunction;
use App\Utils\Loggable\ILoggable;
use App\Utils\Interfaces\IOptimizer;
use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;

class ArtificialBeeColony implements IOptimizer 
{
    private $nBees;
    private $nIterations;
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
    private $abandonment;

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
        $this->abandonment = Math::generateRandomVector($this->nBees, 0);
        $abandonmentLimit = 0.6 * $this->nBees * $this->nDimensions;

        for ($i = 0; $i < $this->nIterations; ++$i) {
            // employed bees
            for ($j = 0; $j < $this->nBees; ++$j) {

                $k = $j;
                $this->updatePositions($k, $j);
            }

            //onlooker bees
            $this->updateFitnesses();

            for ($j = 0; $j < $this->nBees; ++$j) {
                $m = $this->selection->select($this->fitness);
                $k = $m;
                $this->updatePositions($k, $m);
            }

            //scout bees
            for ($j = 0; $j < $this->nBees; ++$j) {
                if ($this->abandonment[$j] >= $abandonmentLimit) {
                    $this->bees[$j]->setPosition(Math::sumNumberToMatrix(Math::generateRandomVector($this->nDimensions, $this->max * 2), -abs($this->min)));
                    $this->bees[$j]->setFitness($this->objectiveFunction($this->bees[$j]->getFitness()));
                    $this->fitness[$j] = $this->bees[$j]->getFitness();
                }
                $this->abandonment[$j] = 0;
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

    private function updatePositions($k, $m){

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
            ++$this->abandonment[$m];
        }
    }

    function getBest() : array{
        return $this->bestBee->getPosition();
    }
}