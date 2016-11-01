<?php
/**
 *** NOT YET FINISHED
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/1/16
 * Time: 11:07 AM
 */

namespace App\ANN;

use App\ANN\Perceptron;
use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;

class SingleLayerPerceptron
{
    private $expectedOutput;
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

    public function setInput(Matrix $input){
        $this->input = $input;
    }

    public function setExpectedOutput($expectedOutput){
        $this->expectedOutput = $expectedOutput;
    }

    public function train(){
        for ($i = 0; $i < $this->input->getN(); ++$i) {

            $columnInput = $this->input->getColumn($i);
            $expectedOutput = $this->expectedOutput->get($i);

            /**
             * Calculate the inputs with the hidden
             * perceptrons. The results are stored in $y
             */
            $y = [];
            foreach ($this->hiddenPerceptrons as $p){
                $p->setInput($columnInput);

                $y[] = $p->calculate();
            }

            /**
             * Get the results of the hidden layer,
             * and set as input for each output Neuron.
             */
            $errorToBeBackpropagated = 0;
            foreach ($this->outputPerceptrons as $op){
                $op = $this->outputPerceptrons[0];
                $op->setInput($y);
                $op->setExpectedOutput($expectedOutput);

                $errorToBeBackpropagated = (new Matrix([$y]))->scalarMultiply($op->calculate()); //* $op->getActivationFunction()->derivative();

            }

            echo $errorToBeBackpropagated;

        }
    }

}