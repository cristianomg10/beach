<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/8/16
 * Time: 2:46 PM
 */

namespace App\Optimizers\ShotgunOptimizer;


use App\Utils\Functions\ObjectiveFunctions\IObjectiveFunction;
use App\Utils\Interfaces\IOptimizer;
use App\Utils\Loggable\ILoggable;
use App\Utils\Math;

class ShotgunOptimizer implements IOptimizer
{

    private $threshold;
    private $nShots;
    private $min;
    private $max;
    private $nIterations;
    private $objFunction;
    private $autoDecay;
    private $rangeShot;
    private $gap;
    private $gapWeight;
    private $penalty;
    private $damp;
    private $bestShot;
    private $shots;
    private $nDimensions;
    private $costs;
    private $bestCost;
    private $loggable;

    function __construct($nShots, $nIterations, $nDimensions, $min, $max, IObjectiveFunction $objFunction, ILoggable $loggable, $autoDecay=1,
                         $rangeShot=0.0001, $gap=2, $penalty=0.5, $threshold=10, $gapWeight=5, $damp=0.8)
    {
        $this->nShots = $nShots;
        $this->nIterations = $nIterations;
        $this->min = $min;
        $this->max = $max;
        $this->objFunction = $objFunction;
        $this->autoDecay = $autoDecay;
        $this->rangeShot = $rangeShot;
        $this->gap = $gap;
        $this->gapWeight = $gapWeight;
        $this->penalty = $penalty;
        $this->threshold = $threshold;
        $this->damp = $damp;
        $this->nDimensions = $nDimensions;
        $this->bestCost = INF;
        $this->loggable = $loggable;
    }

    public function getBest() : array
    {
        return $this->bestShot->getPosition();
    }

    private function initialize(){
        $this->shots = [];
        $this->costs = [];
        $this->bestShot = null;

        for ($i = 0; $i < $this->nShots; ++$i){
            $newPosition = Math::generateRandomVector($this->nDimensions, $this->max + abs($this->min));
            $newPosition = Math::sumNumberToMatrix($newPosition, - abs($this->min));
            $cost = $this->objFunction->compute($newPosition);

            $this->shots[$i] = new Shot($newPosition);
            $this->shots[$i]->setCost($cost);
            $this->costs[$i] = $cost;

            if ($cost < $this->bestCost){
                $this->bestCost = $cost;
                $this->bestShot = $this->shots[$i];
            }
        }
    }

    public function run()
    {
        $cc = 0;
        $minRange = [];
        $maxRange = [];
        $minRangeOut = [];
        $maxRangeOut = [];
        $regulator = 1;

        $this->initialize();

        for ($j = 0; $j < $this->nIterations; ++$j) {
            for ($i = 0; $i < $this->nDimensions; ++$i) {
                ++$regulator;

                $bestSolution = $this->bestShot->getPosition();

                $minRange[$i] = ($bestSolution[$i] - $this->rangeShot) * (1 / $regulator);
                $maxRange[$i] = ($bestSolution[$i] + $this->rangeShot) * (1 / $regulator);

                $minRangeOut[$i] = $bestSolution[$i] - $this->gap;
                $maxRangeOut[$i] = $bestSolution[$i] + $this->gap;
            }

            // Fire
            for ($i = 0; $i < $this->nShots / 2; ++$i) {
                $v1 = [];
                $v2 = [];
                for ($k = 0; $k < $this->nDimensions; ++$k){
                    $v1[$k] = $minRange[$k] + Math::getRandomValue() * ($maxRange[$k] - $minRange[$k]);
                    $v1[$k] = $v1[$k] < $this->min ? $this->min : $v1[$k];
                    $v1[$k] = $v1[$k] > $this->max ? $this->max : $v1[$k];

                    $v2[$k] = $minRangeOut[$k] + Math::getRandomValue() * ($maxRangeOut[$k] - $minRangeOut[$k]);
                    $v2[$k] = $v2[$k] < $this->min ? $this->min : $v2[$k];
                    $v2[$k] = $v2[$k] > $this->max ? $this->max : $v2[$k];
                }

                $this->shots[$i]->setPosition($v1);
                $this->shots[$i]->setCost($this->objFunction->compute($this->shots[$i]->getPosition()));

                $this->shots[$this->nShots/2 + $i]->setPosition($v2);
                $this->shots[$this->nShots/2 + $i]->setCost($this->objFunction->compute($this->shots[$this->nShots/2 + $i]->getPosition()));
            }

            $found = 0;
            for ($i = 0; $i < $this->nShots; ++$i) {
                if ($this->shots[$i]->getCost() < $this->bestCost){
                    $this->bestShot = $this->shots[$i];
                    $this->bestCost = $this->shots[$i]->getCost();
                    $found++;
                    $cc = 0;
                }
            }

            if (!$found){
                $this->rangeShot *= $this->penalty;
                ++$cc;
            }

            if ($cc == $this->threshold){
                if ($this->autoDecay) $this->threshold -= 2;
                $this->gap /= $this->gapWeight;
                $cc = 0;
                $this->rangeShot = 0.0001;
                $regulator = 1;

                for ($i = 0; $i < $this->nShots; ++$i){
                    $newPosition = Math::multiplyNumberToMatrix($this->shots[$i]->getPosition(), -((float)rand()/(float)getrandmax()));
                    $this->shots[$i]->setPosition($newPosition);
                    $this->shots[$i]->setCost($this->objFunction->compute($this->shots[$i]->getPosition()));

                    if ($this->shots[$i]->getCost() < $this->bestCost){
                        $this->bestCost = $this->shots[$i]->getCost();
                        $this->bestShot = $this->shots[$i];
                    }
                }
            }

            if ($this->threshold <= 0) $this->threshold = 1;
            $this->loggable->write("Iteration $j: Best Cost: {$this->bestCost}");
        }
    }
}