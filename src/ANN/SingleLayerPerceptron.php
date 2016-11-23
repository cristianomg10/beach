<?php
/**
 *** NOT YET FINISHED
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/1/16
 * Time: 11:07 AM
 */

namespace App\ANN;

use App\Functions\ActivationFunctions\StepFunction;
use App\ANN\Perceptron;
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

    public function setInput(Matrix $input){
        $this->input = $input;
    }

    public function setExpectedOutput($expectedOutput){
        $this->expectedOutput = $expectedOutput;
    }

    public function train(){

        $epoch = 1;
        /* Run the epochs (batch training) */
        while ($epoch < $this->maxEpochs){
            /*
             *
            X	$$X$$	Input Data, each row in an example	(numExamples, inputLayerSize) OK
            y	$$y$$	target data	(numExamples, outputLayerSize)  OK
            W1	$$W^{(1)}$$	Layer 1 weights	(inputLayerSize, hiddenLayerSize)
            W2	$$W^{(2)}$$	Layer 2 weights	(hiddenLayerSize, outputLayerSize)
            z2	$$z^{(2)}$$	Layer 2 activation	(numExamples, hiddenLayerSize)
            a2	$$a^{(2)}$$	Layer 2 activity	(numExamples, hiddenLayerSize)
            z3	$$z^{(3)}$$	Layer 3 activation	(numExamples, outputLayerSize) OK
            J	$$J$$	Cost	(1, outputLayerSize)
            dJdz3	$$\frac{\partial J}{\partial z^{(3)} } = \delta^{(3)}$$	Partial derivative of cost with respect to $z^{(3)}$	(numExamples,outputLayerSize)
            dJdW2	$$\frac{\partial J}{\partial W^{(2)}}$$	Partial derivative of cost with respect to $W^{(2)}$	(hiddenLayerSize, outputLayerSize)
            dz3dz2	$$\frac{\partial z^{(3)}}{\partial z^{(2)}}$$	Partial derivative of $z^{(3)}$ with respect to $z^{(2)}$	(numExamples, hiddenLayerSize)
            dJdW1	$$\frac{\partial J}{\partial W^{(1)}}$$	Partial derivative of cost with respect to $W^{(1)}$	(inputLayerSize, hiddenLayerSize)
            delta2	$$\delta^{(2)}$$	Backpropagating Error 2	(numExamples,hiddenLayerSize)
            delta3	$$\delta^{(3)}$$	Backpropagating Error 1	(numExamples,outputLayerSize)
             */

            $z3 = [];
            $z2 = [];
            $hiddenOutputs = []; // z3
            $x = $this->input->transpose(); // 4x2
            $y = $this->expectedOutput->asColumnMatrix(); // 4x1
            $a2 = []; // 4 x 3
            $yHat = [];
            $this->output = [];

            // calculate z3 and yHat
            $outputPerceptron = $this->outputPerceptrons[0];

            for ($i = 0; $i < $this->input->getN(); ++$i) {

                $columnInput = $this->input->getColumn($i);
                $expectedOutput = $this->expectedOutput->get($i);

                $inputForOutputLayer = [];
                for ($j = 0; $j < $this->nHiddenPerceptrons; ++$j){
                    $this->hiddenPerceptrons[$j]->setInput($columnInput);
                    $inputForOutputLayer[] = $this->hiddenPerceptrons[$j]->calculate();
                }

                $hiddenOutputs[$i] = $inputForOutputLayer;

                $outputPerceptron->setInput($inputForOutputLayer);
                $outputPerceptron->setExpectedOutput($expectedOutput);

                $z2[$i] = $inputForOutputLayer;
                $z3[] = $outputPerceptron->calculate();
                $this->output[] = (new StepFunction())->compute($outputPerceptron->calculate());
                $a2[$i] = $inputForOutputLayer;

                $yHat[] = $outputPerceptron->calculate();
            }

            // Matrix object doesn't accept multiplication by zero :(
            $this->output = new Matrix([$this->output]);
            $rows = (new Matrix($a2))->getN();

            $w2 = $outputPerceptron->getWeights();
            for ($i = 0; $i < count($w2); ++$i){
                for ($j = 0; $j < $rows; ++$j)
                    $a2[$i][$j] = $a2[$i][$j] * $w2[$i];
            }

            $a2 = (new Matrix($a2));
            $yHat = (new Matrix([$yHat]))->transpose();
            $z3 = (new Matrix([$z3]))->transpose();
            $w2 = (new Matrix([$w2]))->transpose();
            $oldJ = $this->J;
            $this->J = $this->costFunction($y, $yHat);

            if ($oldJ < $this->J){
                $factor = -1;
            }

            // OutputLayer dJdW2
            $hiddenOutputs = (new Matrix($hiddenOutputs));
            $delta3 = ($y->subtract($yHat))
                ->scalarMultiply(-1)
                ->hadamardProduct($this->hiddenLayerActivationFunction->derivative($z3))

            ;

            $dJdW2 = $a2->transpose()->multiply($delta3);
            # till here: WORKING :D
            $z2 = (new Matrix($z2));

            $dJdW1 = $delta3->multiply($w2->transpose())->hadamardProduct($this->hiddenLayerActivationFunction->derivative($z2));
            $dJdW1 = $x->transpose()->multiply($dJdW1);

            // update weights
            $firstLayerWeights = $dJdW1->transpose()->getMatrix();
            for ($i = 0; $i < $this->nHiddenPerceptrons; ++$i){
                $weights = [];
                $w = $this->hiddenPerceptrons[$i]->getWeights();

                for ($j = 0; $j < count($this->hiddenPerceptrons[$i]->getWeights()); ++$j) {
                    $weights[] = $w[$j] + $firstLayerWeights[$i][$j] * $factor;
                }

                $this->hiddenPerceptrons[$i]->setWeights($weights);
            }

            $outputLayerWeights = $dJdW2->transpose()->getMatrix();
            for ($i = 0; $i < $this->nOutputPerceptrons; ++$i){
                $weights = [];
                $w = $this->outputPerceptrons[$i]->getWeights();

                for ($j = 0; $j < count($this->outputPerceptrons[$i]->getWeights()); ++$j) {
                    $weights[] = $w[$j] + $outputLayerWeights[$i][$j] * $factor;
                }

                $this->outputPerceptrons[$i]->setWeights($weights);
            }

            echo "Epoca $epoch: <br>";
            echo $this;
            ++$epoch;
        }
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