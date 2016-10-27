<?php
use \MathPHP\LinearAlgebra\Matrix;
use \MathPHP\LinearAlgebra\Vector;

require_once 'Perceptron.class.php';
require_once 'IActivationFunction.php';
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/26/16
 * Time: 5:09 PM
 */
class SingleLayerPerceptron
{
    private $input;
    private $perceptrons;
    private $maxEpochs;
    private $learningRate;
    private $momentumRate;
    private $expectedOutput;
    private $activationFunction;

    /**
     * SingleLayerPerceptron constructor.
     * @param int $nPerceptrons Number of perceptrons to be created
     */
    function __construct(int $nPerceptrons, int $maxEpochs)
    {
        for ($i = 0; $i < $nPerceptrons; ++$i){
            $this->perceptrons[] = new Perceptron();
        }

        $this->maxEpochs = $maxEpochs;
    }

    function setInput(Matrix $input){
        $this->input = $input;
        $this->initialize();
    }

    public function setLearningRate($learningRate){
        $this->learningRate = $learningRate;
    }

    public function setMomentumRate($momentumRate){
        $this->momentumRate = $momentumRate;
    }

    public function setExpectedOutput($expectedOutput){
        $this->expectedOutput = $expectedOutput;
    }

    private function initialize(){
        $weights = [];
        for ($j = 0; $j < count($this->perceptrons); ++$j) {
            for ($i = 0; $i < $this->input->getM(); ++$i) {
                $weights[] = mt_rand() / mt_getrandmax();
            }

            $this->perceptrons[$j]->setWeights($weights);
            $this->perceptrons[$j]->setBias(mt_rand() / mt_getrandmax());
        }
    }

    private function log($string){
        echo $string . "<br>";
    }

    public function setActivationFunction (IActivationFunction $af){
        $this->activationFunction = $af;
    }

    public function train(){
        $err = 1000;
        $epoch = 1;

        while ($err > 0 && $epoch <= $this->maxEpochs) {
            $err = 0;


            for ($i = 0; $i < $input->getN(); ++$i){
                #$beta			= mt_rand() / mt_getrandmax();
                $columnInput = $input->getColumn($i);

                $p->setInput($columnInput);
                $p->setExpectedOutput($expectedOutput->get($i));
                $p->setBias($bias);
                $p->setWeights($weights->getVector());
                $p->calculate();

                echo "Input: " . new Vector($input->getColumn($i)) . "<br>";
                echo "Expected output: " . $expectedOutput->get($i) . "<br>";
                echo "Returned output: " . $p->getOutput() . "<br>";

                $err += $p->getSquareError();

                $prepWeights = null;
                $prepWeights = new Vector($columnInput);
                $prepWeights = $prepWeights->scalarMultiply($p->getError());
                $prepWeights = $prepWeights->scalarMultiply($learningRate);

                $weights = $weights->add($prepWeights);
                $bias = $bias + $learningRate * $p->getError();

                if (isset($beta)){
                    $prepWeights = null;
                    $prepWeightsBeta = new Vector($columnInput);
                    $prepWeightsBeta = $prepWeightsBeta->scalarMultiply($p->getError());
                    $prepWeightsBeta = $prepWeightsBeta->scalarMultiply($beta);

                    $weights = $weights->add($prepWeightsBeta);
                    $bias = $bias + $beta * $p->getError();
                }

                echo "Weights: $weights<br>";
                echo "Bias: $bias<br>";

                echo "<br>";
            }
            echo "****** FINISHING EPOCH: $epoch. ERROR: $err<br>";
            ++$epoch;
        }
    }
}