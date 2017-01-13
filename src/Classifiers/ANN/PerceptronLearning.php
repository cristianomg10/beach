<?php
namespace App\Classifiers\ANN;

use App\Utils\Interfaces\IClassifier;
use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;
use App\Classifiers\ANN\Perceptron;
use App\Utils\Loggable\ILoggable;
use App\Utils\Functions\ActivationFunctions\IActivationFunction;

/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/26/16
 * Time: 5:09 PM
 */
class PerceptronLearning implements IClassifier
{
    private $expectedOutput;
    private $input;
    private $learningRate;
    private $maxEpochs;
    private $momentumRate;
    private $perceptron;
    private $sizeExpectedOutput;
    private $loggable;

    /**
     * PerceptronLearning constructor.
     * @param int $maxEpochs
     * @param IActivationFunction $af
     * @param $expectedOutput
     */
    function __construct(int $maxEpochs, IActivationFunction $af, $expectedOutput = [])
    {
        $this->perceptron = new Perceptron();
        $this->perceptron->setActivationFunction($af);

        $this->maxEpochs = $maxEpochs;

        if ($expectedOutput != []){
            $this->setExpectedOutput($expectedOutput);
        }
    }

    /**
     * @param ILoggable $loggable
     */
    function setLoggable(ILoggable $loggable){
        $this->loggable = $loggable;
    }

    /**
     * Input Matrix
     * @param Matrix $input
     */
    function setInput(Matrix $input){
        $this->input = $input;
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
    public function setExpectedOutput(Matrix $expectedOutput){
        $this->expectedOutput = $expectedOutput;

        if (get_class($expectedOutput) == Matrix::class){
            $this->sizeExpectedOutput = $expectedOutput->getM();
        } else if (get_class($expectedOutput) == Vector::class){
            $this->sizeExpectedOutput = 1;
        }
    }


    private function write($text){
        if (isset($this->loggable)) $this->loggable->write($text);
    }

    /**
     * Initialize the perceptrons;
     */
    private function initialize(){
        $weights = [];
        for ($i = 0; $i < $this->input->getM(); ++$i) {
            $weights[] = Math::getRandomValue();
        }

        $this->perceptron->setWeights($weights);
        $this->perceptron->setBias(Math::getRandomValue());

    }

    /**
     * Classify using the Perceptron.
     */
    public function run(){

        $output = [];

        for ($i = 0; $i < $this->input->getN(); ++$i){
            $columnInput = $this->input->getColumn($i);

            $p = $this->perceptron;
            $p->setInput($columnInput);
            $p->calculate();
            $output[] = $p->getOutput();

        }
        return new Vector($output);

    }

    /**
     * Train the perceptrons :)
     */
    public function learn()
    {
        $this->initialize();
        $err = 1000;
        $epoch = 1;

        while ($err > 0 && $epoch <= $this->maxEpochs) {
            $err = 0;

            for ($i = 0; $i < $this->input->getN(); ++$i) {
                $columnInput = $this->input->getColumn($i);

                $p = $this->perceptron;
                $p->setInput($columnInput);
                $p->setExpectedOutput($this->expectedOutput->get(0, $i));
                $p->calculate();

                $this->write("Input: " . new Vector($this->input->getColumn($i)));
                $this->write("Expected output: " . $this->expectedOutput->get(0, $i));
                $this->write("Returned output: " . $p->getOutput());

                $err += $p->getSquareError();

                $prepWeights = null;
                $prepWeights = new Vector($columnInput);
                $prepWeights = $prepWeights->scalarMultiply($p->getError());
                $prepWeights = $prepWeights->scalarMultiply($this->learningRate);

                $weights = (new Vector($p->getWeights()))->add($prepWeights);
                $bias = $p->getBias() + $this->learningRate * $p->getError();

                if (isset($this->momentumRate)) {
                    $prepWeights = null;
                    $prepWeightsBeta = new Vector($columnInput);
                    $prepWeightsBeta = $prepWeightsBeta->scalarMultiply($p->getError());
                    $prepWeightsBeta = $prepWeightsBeta->scalarMultiply($this->momentumRate);

                    $weights = $weights->add($prepWeightsBeta);
                    $bias = $bias + $this->momentumRate * $p->getError();
                }

                $p->setBias($bias);
                $p->setWeights($weights->getVector());

                $this->write("Weights: $weights");
                $this->write("Bias: $bias");
                $this->write("");
            }

            $this->write("****** FINISHING EPOCH: $epoch. ERROR: $err");
            ++$epoch;
        }
    }

    public function classify(Matrix $input)
    {
        $output = [];

        for ($i = 0; $i < $input->getN(); ++$i){
            $columnInput = $input->getColumn($i);

            $p = $this->perceptron;
            $p->setInput($columnInput);
            $p->calculate();
            $output[] = $p->getOutput();

        }
        return new Matrix([$output]);

    }
}