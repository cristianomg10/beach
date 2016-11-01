<?php
namespace App\ANN;
/**
 **** NOT YET FINISHED
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/27/16
 * Time: 9:56 PM
 */

use \MathPHP\LinearAlgebra\Matrix;
use \MathPHP\LinearAlgebra\Vector;
use App\ActivationFunctions\IActivationFunction;

class ELM {
    private $nHiddenNodes;
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
    public function setInput($input)
    {
        $this->input = $input;
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
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @return mixed
     */
    public function getNHiddenNodes()
    {
        return $this->nHiddenNodes;
    }

    /**
     * @param mixed $nHiddenNodes
     */
    public function setNHiddenNodes($nHiddenNodes)
    {
        $this->nHiddenNodes = $nHiddenNodes;
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
     * @param $nHiddenNodes
     * @param $c
     * @param IActivationFunction $activationFunction
     */
    function  __construct($nHiddenNodes, $c, IActivationFunction $activationFunction){
        $this->nHiddenNodes = $nHiddenNodes;
        $this->c = $c;
        $this->activationFunction = $activationFunction;
    }

    function learn(){
        $nOutputNeurons = $this->output + 1;
    }
}