<?php
/**
 *** NOT YET FINISHED
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/1/16
 * Time: 11:07 AM
 */

namespace App\Classifiers\ANN;

use App\Utils\Exceptions\IllegalArgumentException;
use App\Utils\Functions\ActivationFunctions\StepFunction;
use App\Classifiers\ANN\Perceptron;
use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

class SingleLayerPerceptron
{
    private $expectedOutput;
    private $output;
    private $input;
    private $inputLayerSize;
    private $learningRate;
    private $loggable;
    private $maxEpochs;
    private $momentumRate;
    private $hiddenPerceptrons = [];
    private $outputPerceptrons = [];
    private $nOutputPerceptrons;
    private $nHiddenPerceptrons;
    private $hiddenLayerActivationFunction;
    private $outputLayerActivationFunction;
    private $J;

    /**
     * SingleLayerPerceptron constructor. First implemented to only one neuron in the output layer.
     * @param $nHiddenPerceptrons
     * @param $hiddenLayerActivationFunction
     * @param $outputLayerActivationFunction
     * @param $learningRate
     * @param $momentumRate
     * @param $nOutputPerceptrons
     */
    public function __construct($nHiddenPerceptrons, $hiddenLayerActivationFunction,
                                $outputLayerActivationFunction, $learningRate, $momentumRate, $inputLayerSize, $nOutputPerceptrons, $maxEpochs)
    {
        $this->nHiddenPerceptrons = $nHiddenPerceptrons;
        $this->hiddenLayerActivationFunction = $hiddenLayerActivationFunction;
        $this->outputLayerActivationFunction = $outputLayerActivationFunction;
        $this->learningRate = $learningRate;
        $this->momentumRate = $momentumRate;
        $this->nOutputPerceptrons = $nOutputPerceptrons;
        $this->inputLayerSize = $inputLayerSize;
        $this->maxEpochs = $maxEpochs;

        $this->initializeHiddenLayer();
        $this->initializeOutputLayer();
    }

    private function log($text){
        echo "$text<br>";
    }

    public function __toString() : string
    {
        $text = '';
        $text .= "Hidden Layer: <br>";
        for ($i = 0; $i < $this->nHiddenPerceptrons; ++$i){
            $text .= ">> Perceptron $i: " . new Matrix([$this->hiddenPerceptrons[$i]->getWeights()]) . "<br>";
        }

        $text .= "Output Layer: <br>";
        for ($i = 0; $i < $this->nOutputPerceptrons; ++$i){
            $text .= ">> Perceptron $i: " . new Matrix([$this->outputPerceptrons[$i]->getWeights()]) . "<br>";
        }

        $text .= "Input: {$this->input}<br>";
        $text .= "Output: {$this->output}<br>";

        $text .= "<br>J: {$this->J}<br>";
        return $text;
    }

    /**
     *
     */
    private function initializeHiddenLayer(){
        for ($i = 0; $i < $this->nHiddenPerceptrons; ++$i){
            $p = new Perceptron();
            $p->setBias(1);
            $p->setWeights(Math::generateRandomVector($this->inputLayerSize, 1));
            $p->setActivationFunction($this->hiddenLayerActivationFunction);

            $this->hiddenPerceptrons[$i] = $p;
        }
    }

    /**
     *
     */
    private function initializeOutputLayer(){
        for ($i = 0; $i < $this->nOutputPerceptrons; ++$i){
            $p = new Perceptron();
            $p->setActivationFunction($this->outputLayerActivationFunction);
            $p->setBias(1);
            $p->setWeights(
                Math::generateRandomVector($this->nHiddenPerceptrons, 1)
            );

            $this->outputPerceptrons[$i] = $p;
        }
    }

    public function getWeights(){
        $weights = [];

        foreach ($this->hiddenPerceptrons as $h){
            $weights += $h->getWeights();
            $weights[] = $h->getBias();
        }

        foreach ($this->outputPerceptrons as $h){
            $weights += $h->getWeights();
            $weights[] = $h->getBias();
        }

        return $weights;
    }

    public function getWeightSize(){
        $qty = $this->nHiddenPerceptrons * $this->inputLayerSize + $this->nHiddenPerceptrons * $this->nOutputPerceptrons;
        $qty += ($this->nHiddenPerceptrons + $this->nOutputPerceptrons); // biases

        return $qty;
    }

    public function setWeights($weights){
        $count = count($weights);
        $qty = $this->nHiddenPerceptrons * $this->inputLayerSize + $this->nHiddenPerceptrons * $this->nOutputPerceptrons;
        $qty += ($this->nHiddenPerceptrons + $this->nOutputPerceptrons); // biases

        if ($count != $qty){
            throw new IllegalArgumentException("Weights of different size. It's needed $qty weights. $count were provided.");
        }

        $place = 0;
        foreach ($this->hiddenPerceptrons as $h){
            $h->setWeights(array_slice($weights, $place, $this->inputLayerSize));
            $place += $this->inputLayerSize;
            $h->setBias($weights[$place++]);
        }

        foreach ($this->outputPerceptrons as $h){
            $h->setWeights(array_slice($weights, $place, $this->nHiddenPerceptrons));
            $place += $this->nHiddenPerceptrons;
            $h->setBias($weights[$place++]);
        }
    }

    public function setInput(Matrix $input){
        $this->input = $input;
    }



    public function setExpectedOutput($expectedOutput){
        $this->expectedOutput = $expectedOutput;
    }

    public function getInputSize(){
        return $this->input->getN();
    }

    public function getHiddenNodesCount(){
        return $this->nHiddenPerceptrons;
    }

    public function train(){

    }

    public function run($input, $expectedOutput = ''){
        $columnInput = $input;
        $outputPerceptron = $this->outputPerceptrons[0];

        $inputForOutputLayer = [];
        for ($j = 0; $j < $this->nHiddenPerceptrons; ++$j){
            $this->hiddenPerceptrons[$j]->setInput($columnInput);
            $inputForOutputLayer[] = $this->hiddenPerceptrons[$j]->calculate();
        }

        $outputPerceptron->setInput($inputForOutputLayer);

        $yHat = $outputPerceptron->calculate();

        return $yHat;
    }

    private function costFunction($y, $yHat){
        $element = $y->subtract($yHat)->getMatrix();

        $sum = 0;
        for ($i = 0; $i < count($element); ++$i){
            for ($j = 0; $j < count($element[$i]); ++$j){
                $sum += $element[$i][$j];
            }
        }
        return 0.5 * $sum ** 2;
    }

}