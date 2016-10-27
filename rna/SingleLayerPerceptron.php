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
    private $activationFunction;
    private $expectedOutput;
    private $input;
    private $learningRate;
    private $maxEpochs;
    private $momentumRate;
    private $perceptrons;
    private $sizeExpectedOutput;

    /**
     * SingleLayerPerceptron constructor.
     * @param int $nPerceptrons
     * @param int $maxEpochs
     * @param IActivationFunction $af
     */
    function __construct(int $nPerceptrons, int $maxEpochs, IActivationFunction $af, $expectedOutput)
    {
        $this->perceptrons = [];

        for ($i = 0; $i < $nPerceptrons; ++$i){
            $this->perceptrons[$i] = new Perceptron();
            $this->perceptrons[$i]->setActivationFunction($af);
        }

        $this->maxEpochs = $maxEpochs;
        $this->setExpectedOutput($expectedOutput);
    }

    /**
     * Input Matrix
     * @param Matrix $input
     */
    function setInput(Matrix $input){
        $this->input = $input;
        $this->initialize();
    }

    /**
     * Set the Learning Rate of the Algorithm
     * @param $learningRate
     */
    public function setLearningRate($learningRate){
        $this->learningRate = $learningRate;
    }

    /**
     * Set the Momentum Rate of the Algorithm
     * @param $momentumRate
     */
    public function setMomentumRate($momentumRate){
        $this->momentumRate = $momentumRate;
    }

    /**
     * Set expected output for respective inputs.
     * @param $expectedOutput (Matrix or Vector)
     */
    private function setExpectedOutput($expectedOutput){
        $this->expectedOutput = $expectedOutput;

        if (is_a(Matrix::class, $expectedOutput)){
            $this->sizeExpectedOutput = $expectedOutput->getM();
        } else if (is_a(Vector::class, $expectedOutput)){
            $this->sizeExpectedOutput = 1;
        }
    }

    /**
     * Initialize the perceptrons;
     */
    private function initialize(){
        for ($j = 0; $j < count($this->perceptrons); ++$j) {
            $weights = [];
            for ($i = 0; $i < $this->input->getM(); ++$i) {
                $weights[] = mt_rand() / mt_getrandmax();
            }

            $this->perceptrons[$j]->setWeights($weights);
            $this->perceptrons[$j]->setBias(mt_rand() / mt_getrandmax());
        }
    }

    /**
     * Log (or write) somewhere
     * @param $string
     */
    private function log($string){
        echo $string . "<br>";
    }

    /**
     * Classify using the SLP.
     */
    public function run(){

        for ($i = 0; $i < $this->input->getN(); ++$i){
            $columnInput = $this->input->getColumn($i);

            for ($j = 0; $j < count($this->perceptrons); ++$j){
                $p = $this->perceptrons[$j];
                $p->setInput($columnInput);
                $p->calculate();

                echo "Input: " . new Vector($this->input->getColumn($i)) . " | Returned output: " . $p->getOutput() . "<br>";
            }
        }
    }

    /**
     * Train the perceptrons :)
     */
    public function train(){
        $err = 1000;
        $epoch = 1;

        while ($err > 0 && $epoch <= $this->maxEpochs) {
            $err = 0;

            for ($i = 0; $i < $this->input->getN(); ++$i){
                $columnInput = $this->input->getColumn($i);

                for ($j = 0; $j < count($this->perceptrons); ++$j){
                    $p = $this->perceptrons[$j];
                    $p->setInput($columnInput);
                    $p->setExpectedOutput($this->expectedOutput->get($i));
                    $p->calculate();

                    echo "Input: " . new Vector($this->input->getColumn($i)) . "<br>";
                    echo "Expected output: " . $this->expectedOutput->get($i) . "<br>";
                    echo "Returned output: " . $p->getOutput() . "<br>";

                    $err += $p->getSquareError();

                    $prepWeights = null;
                    $prepWeights = new Vector($columnInput);
                    $prepWeights = $prepWeights->scalarMultiply($p->getError());
                    $prepWeights = $prepWeights->scalarMultiply($this->learningRate);

                    $weights = (new Vector($p->getWeights()))->add($prepWeights);
                    $bias = $p->getBias() + $this->learningRate * $p->getError();

                    if (isset($this->momentumRate)){
                        $prepWeights = null;
                        $prepWeightsBeta = new Vector($columnInput);
                        $prepWeightsBeta = $prepWeightsBeta->scalarMultiply($p->getError());
                        $prepWeightsBeta = $prepWeightsBeta->scalarMultiply($this->momentumRate);

                        $weights = $weights->add($prepWeightsBeta);
                        $bias = $bias + $this->momentumRate * $p->getError();
                    }

                    $p->setBias($bias);
                    $p->setWeights($weights->getVector());

                    echo "Weights: $weights<br>";
                    echo "Bias: $bias<br>";

                    echo "<br>";
                }
            }
            echo "****** FINISHING EPOCH: $epoch. ERROR: $err<br>";
            ++$epoch;
        }
    }
}