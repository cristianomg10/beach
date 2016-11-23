<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/22/16
 * Time: 5:12 PM
 */

namespace App\ParticleSwarmOptimization;

use App\ParticleSwarmOptimization\ObjectiveFunctions\IObjectiveFunction;
use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;

class ParticleSwarmOptimization
{
    private $nParticles;
    private $nIterations;
    private $c1;
    private $c2;
    private $nDimensions;
    private $objectiveFunction;
    private $particles;
    private $best = INF;
    private $bestParticle;
    private $fitness;
    private $w; // inertia term
    private $min;
    private $max;
    private $wDamp;

    function __construct($nParticles, $nIterations, $c1, $c2, $nDimensions, IObjectiveFunction $objectiveFunction, $w, $wDamp, $min, $max)
    {
        $this->c1 = $c1;
        $this->c2 = $c2;
        $this->nDimensions = $nDimensions;
        $this->nIterations = $nIterations;
        $this->objectiveFunction = $objectiveFunction;
        $this->nParticles = $nParticles;
        $this->w = $w;
        $this->wDamp = $wDamp;
        $this->min = $min;
        $this->max = $max;
    }

    private function initialize(){
        for ($i = 0; $i < $this->nParticles; ++$i){
            $this->particles[$i] = new Particle(
                Math::sumNumberToMatrix(Math::generateRandomVector($this->nDimensions, $this->max * 2), - abs($this->min)),
                Math::generateRandomVector($this->nDimensions, 0)
            );

            $this->fitness[$i] = $this->objectiveFunction->compute($this->particles[$i]->getLocation());
            $this->particles[$i]->setFitness($this->fitness[$i]);
            if ($this->fitness[$i] < $this->best){
                $this->bestParticle = $this->particles[$i];
                $this->best = $this->fitness[$i];
            }
        }

        $this->update();
    }

    private function update(){
        for ($i = 0; $i < $this->nParticles; ++$i){
            $this->fitness[$i] = $this->objectiveFunction->compute($this->particles[$i]->getLocation());
            $this->particles[$i]->setFitness($this->fitness[$i]);
            if ($this->fitness[$i] < $this->best){
                $this->bestParticle = $this->particles[$i];
                $this->best = $this->fitness[$i];
            }
        }
    }


    function run(){
        $this->initialize();

        for ($i = 0; $i < $this->nIterations; ++$i){
            for ($j = 0; $j < $this->nParticles; ++$j){
                $this->update();
                $inertia = Math::multiplyNumberToMatrix($this->particles[$j]->getVelocity(), $this->w);
                $cognitiveComponent = Math::multiplyNumberToMatrix(Math::generateRandomVector($this->nDimensions), $this->c1);
                $socialComponent = Math::multiplyNumberToMatrix(Math::generateRandomVector($this->nDimensions), $this->c2);

                $inertia = new Matrix([$inertia]);
                $cognitiveComponent = new Matrix([$cognitiveComponent]);
                $socialComponent = new Matrix([$socialComponent]);

                //velocity = w*velocity + c1*rand([1xnDimensions]) .* (particle.best.position - particle.position)
                // + c2*rand([1xnDimensions]) .* (best.position - particle.position)
                $innerBestLocation = new Matrix([$this->particles[$j]->getBest()->getLocation()]);
                $particleLocation = new Matrix([$this->particles[$j]->getLocation()]);

                $globalBestLocation = new Matrix([$this->bestParticle->getLocation()]);

                $component1 = $cognitiveComponent
                    ->hadamardProduct($innerBestLocation->subtract($particleLocation));

                $component2 = $socialComponent
                    ->hadamardProduct($globalBestLocation->subtract($particleLocation));

                $newVelocity = $inertia->add($component1)->add($component2);

                $this->particles[$j]->setVelocity(
                    $newVelocity->getRow(0)//->getMatrix()
                );

                $particleLocation = new Matrix([$this->particles[$j]->getLocation()]);
                $this->particles[$j]->setLocation(
                    $particleLocation->add($newVelocity)->getRow(0)//->getMatrix()
                );
            }

            echo "Melhor fitness: " . $this->best . "\n";
            $this->w = $this->w * $this->wDamp;
        }
    }

    /**
     * @return mixed
     */
    public function getBestParticle()
    {
        return $this->bestParticle;
    }

}