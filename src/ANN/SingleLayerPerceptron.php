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
            $accumulatedError = [];
            $output = [];
            $hiddenOutputs = [];

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

                $output[] = $outputPerceptron->getActivationFunction()->derivative($outputPerceptron->calculate());
                $accumulatedError[] = $outputPerceptron->getError();

            }

            // OutputLayer dJdW2
            $hiddenOutputs = (new Matrix($hiddenOutputs));

            $delta3 = (new Matrix([$accumulatedError]))
                ->scalarMultiply(-1)
                ->hadamardProduct(new Matrix([$output]));

            $dJdW2 = [];

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

            $outputPerceptron = $this->outputPerceptrons[0];
            $delta2 = (new Matrix([$outputPerceptron->getWeights()]));
            echo ("Delta2: $delta2, Delta3: $delta3<br>");

            ++$epoch;
        }
    }

}