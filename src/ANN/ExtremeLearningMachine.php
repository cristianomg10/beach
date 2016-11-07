<?php
namespace App\ANN;
/**
 **** NOT YET FINISHED
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/27/16
 * Time: 9:56 PM
 */

use App\Utils\Math;
use \MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use \MathPHP\LinearAlgebra\Vector;
use App\ActivationFunctions\IActivationFunction;

class ExtremeLearningMachine {
    private $nHiddenPerceptrons;
    private $hiddenPerceptrons;
    private $activationFunction;
    private $c = 1;
    private $bias;
    private $inputWeight;
    private $outputWeight;
    private $input;
    private $output;

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param mixed $input
     */
    public function setInput(Matrix $input)
    {
        $this->input = $input->transpose();
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param mixed $output
     */
    public function setOutput(Matrix $output)
    {
        $this->output = $output->transpose();
    }

    /**
     * @return mixed
     */
    public function getNHiddenPerceptrons()
    {
        return $this->nHiddenPerceptrons;
    }

    /**
     * @param mixed $nHiddenPerceptrons
     */
    public function setNHiddenPerceptrons($nHiddenPerceptrons)
    {
        $this->nHiddenPerceptrons = $nHiddenPerceptrons;
    }

    /**
     * @return mixed
     */
    public function getActivationFunction()
    {
        return $this->activationFunction;
    }

    /**
     * @param mixed $activationFunction
     */
    public function setActivationFunction($activationFunction)
    {
        $this->activationFunction = $activationFunction;
    }

    /**
     * @return int
     */
    public function getC(): int
    {
        return $this->c;
    }

    /**
     * @param int $c
     */
    public function setC(int $c)
    {
        $this->c = $c;
    }

    /**
     * @return mixed
     */
    public function getBias()
    {
        return $this->bias;
    }

    /**
     * @param mixed $bias
     */
    public function setBias($bias)
    {
        $this->bias = $bias;
    }

    /**
     * @return mixed
     */
    public function getInputWeight()
    {
        return $this->inputWeight;
    }

    /**
     * @param mixed $inputWeight
     */
    public function setInputWeight($inputWeight)
    {
        $this->inputWeight = $inputWeight;
    }

    /**
     * @return mixed
     */
    public function getOutputWeight()
    {
        return $this->outputWeight;
    }

    /**
     * @param mixed $outputWeight
     */
    public function setOutputWeight($outputWeight)
    {
        $this->outputWeight = $outputWeight;
    }

    /**
     * ELM constructor.
     * @param $nHiddenPerceptrons
     * @param $c
     * @param IActivationFunction $activationFunction
     */
    function  __construct($nHiddenPerceptrons, $c, IActivationFunction $activationFunction){
        $this->nHiddenPerceptrons = $nHiddenPerceptrons;
        $this->c = $c;
        $this->activationFunction = $activationFunction;
    }

    function learn(){
        // Create perceptrons;
        for ($i = 0; $i < $this->nHiddenPerceptrons; ++$i){
            $p = new Perceptron();
            $p->setBias(Math::getRandomValue());
            $p->setWeights(Math::generateRandomVector($this->input->getM()));
            $p->setActivationFunction($this->activationFunction);
            $this->hiddenPerceptrons[$i] = $p;
        }

        // Run perceptrons
        $h = [];
        for ($i = 0; $i < $this->input->getM(); ++$i){
            for ($j = 0; $j <  $this->nHiddenPerceptrons; ++$j){
                $this->hiddenPerceptrons[$j]->setInput($this->input->getRow($i));
                $h[$i][$j] = $this->hiddenPerceptrons[$j]->calculate();
            }
        }

        // Add regularization factor
        $h = new Matrix($h);
        $b = $h->multiply($h->transpose())->getMatrix();

        for ($i = 0; $i < count($b); ++$i){
            $b[$i][$i] += 1.0 / $this->c;
        }

        $b = new Matrix($b);
        //till here: nice

        $temp = $b->inverse()->multiply($h)->getMatrix();
        $output = $this->output->transpose()->getMatrix();
        //echo new Matrix($temp) . "<br>";
        //echo new Matrix($output) . "<br>";
        $outputWeights = [];

        for ($i = 0; $i < count($temp); ++$i){
            for ($j = 0; $j < count($temp[$i]); ++$j){
                //echo "temp[$i][$j]: {$temp[$i][$j]} * output[0][$i]: {$output[0][$i]} <br>";
                $outputWeights[$i][$j] = $temp[$i][$j] * $output[0][$i];
            }
        }

        $outputWeights = new Matrix($outputWeights);
        echo $outputWeights;
    }
}