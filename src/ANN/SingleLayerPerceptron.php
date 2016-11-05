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
use MathPHP\LinearAlgebra\MatrixFactory;
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
            $hiddenOutputs = []; // z3
            $x = $this->input->transpose(); // 4x2
            $y = $this->expectedOutput->asColumnMatrix(); // 4x1
            $yHat = [];

            // calculate z3 and yHat
            for ($i = 0; $i < $this->input->getN(); ++$i) {

                $columnInput = $this->input->getColumn($i);
                $expectedOutput = $this->expectedOutput->get($i);

                $inputForOutputLayer = [];
                for ($j = 0; $j < $this->nHiddenPerceptrons; ++$j){
                    $this->hiddenPerceptrons[$j]->setInput($columnInput);
                    $inputForOutputLayer[] = $this->hiddenPerceptrons[$j]->calculate();
                }
                $hiddenOutputs[$i] = $inputForOutputLayer;

                $outputPerceptron = $this->outputPerceptrons[0];
                $outputPerceptron->setInput($inputForOutputLayer);
                $outputPerceptron->setExpectedOutput($expectedOutput);

                $z3[] = $outputPerceptron->getActivationFunction()->derivative($outputPerceptron->calculate());
                $yHat[] = $outputPerceptron->getError();
            }

            echo ($y);
            echo (new Matrix([$yHat]));

            $z3 = (new Matrix([$z3]));
            die($y->subtract(new Matrix([$yHat]))->transpose());
            //stopped here

            // OutputLayer dJdW2
            $hiddenOutputs = (new Matrix($hiddenOutputs));

            $delta3 = (new Matrix([$yHat]))
                ->scalarMultiply(-1)
                ->hadamardProduct(new Matrix([$z3]))->transpose();

            $dJdW2 = [];

            die($hiddenOutputs);


            /*
            echo "$delta3 <br>";
            echo "$hiddenOutputs {$hiddenOutputs->getM()}x{$hiddenOutputs->getN()}<br>";
            echo "{$hiddenOutputs->transpose()} {$hiddenOutputs->transpose()->getM()}x{$hiddenOutputs->transpose()->getN()}<br>";
            */
            for ($j = 0; $j < $hiddenOutputs->getN(); ++$j){ //  1 * (3)
                for ($k = 0; $k < $delta3->getN(); ++$k){ // 3 * (4)
                    if (!isset($dJdW2[$j])) $dJdW2[$j] = 0;
                    $dJdW2[$j] +=  $hiddenOutputs->transpose()->get($j, $k) * $delta3->get(0, $j);
                    //echo "Result:$dJdW2[$j] j:$j k:$k delta3(0,$j):{$delta3->get(0, $j)} hiddenOutputs->transpose->get($j, $k):{$hiddenOutputs->transpose()->get($j, $k)} <br>";
                }
            }

            // first layer
            $xTdelta3 = [];

            for ($j = 0; $j < $this->input->getM(); ++$j){
                for ($k = 0; $k < $this->input->getN(); ++$k){
                    $xTdelta3[$j][$k] = $this->input->get($j, $k) * $delta3->get(0, $k);
                }
            }

            $z2 = [];
            $z2p = []; //z2 prime
            for ($j = 0; $j < $this->nHiddenPerceptrons; ++$j){
                $weights = $this->hiddenPerceptrons[$j]->getWeights();
                for ($k = 0; $k < count($weights); ++$k) {
                    echo "$j $k <br>";
                    $z2[$j][] = $weights[$k] * $this->input->transpose()->get($j, $k);
                    $z2p[$j][] = $this->hiddenPerceptrons[$j]->getActivationFunction()->derivative($weights[$k]);
                }
            }

            $z2 = new Matrix($z2);
            $z2p = new Matrix($z2p);
            $xTdelta3 = new Matrix($xTdelta3);


            echo "$xTdelta3 <br>";
            echo "$z2p <br>";
            die($z2p->multiply($xTdelta3));

            $outputPerceptron = $this->outputPerceptrons[0];
            $delta2 = (new Matrix([$outputPerceptron->getWeights()]))->transpose()->multiply($delta3); // w(2)T x d3
            $input  = $this->input->transpose();
            $dJdW1 = [];

            echo $delta2;
            echo ($this->input);
            die("Ue");
            echo "{$delta2->multiply($this->input->transpose())}";

            for ($j = 0; $j < $hiddenOutputs->getN(); ++$j){ //  1 * (3)
                for ($k = 0; $k < $delta2->getN(); ++$k){ // 3 * (4)
                    if (!isset($dJdW1[$j])) $dJdW1[$j] = 0;
                    $dJdW1[$j] +=  $hiddenOutputs->transpose()->get($j, $k) * $delta2->get(0, $j);
                    //echo "Result:$dJdW2[$j] j:$j k:$k delta3(0,$j):{$delta3->get(0, $j)} hiddenOutputs->transpose->get($j, $k):{$hiddenOutputs->transpose()->get($j, $k)} <br>";
                }
            }


            //echo ("Delta2: $delta2<br>Delta3: $delta3 <br> Input: $input<br>");
            echo "dJdW1: " . new Matrix([$dJdW1]) . "<br>";
            die();
            ++$epoch;
        }
    }

}