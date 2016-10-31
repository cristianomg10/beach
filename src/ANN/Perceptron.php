<?php
namespace App\ANN;

use App\ActivationFunctions\IActivationFunction;

class Perceptron {
	private $weights;
	private $input;
	private $output;
	private $expectedOutput;
	private $bias;
	private $activationFunction;

	/**
	 * Input of the neuron. 
	 * @param mixed $input Example: $input	= [0, 1]; // Simulating logic inputs
	 */
	public function setInput($input){
		$this->input = $input;
	}

  	/**
  	 * Receives the expected output of the neuron. Needed for training;
  	 * @param Array $expectedOutput Example: $expectedOutput = [0, 0, 0, 1]; // simulating logic outputs
  	 */
	public function setExpectedOutput($expectedOutput){
		$this->expectedOutput = $expectedOutput;
	}

	/**
	 * Returns the values of the output
	 * @return Array
	 */
	public function getOutput(){
		return $this->output;
	}

	/**
	 * Set values for the weights
	 * @param Array $weights Values of weights
	 */
	public function setWeights(Array $weights){
		$this->weights = $weights;
	}

	/**
	 * Set value for bias. Ex.: 0.3
	 * @param $value [description]
	 */
	public function setBias($value){
		$this->bias = $value;
	}

	/**
	 * Train :)
	 */
	public function calculate(){
		//setting value as as 0
		$this->output = 0;

		for ($i = 0; $i < count($this->input); ++$i){
			$this->output += $this->weights[$i] * $this->input[$i];
		}

		$this->output += $this->bias;
		$this->output = $this->activationFunction->activationFunction($this->output);

        return $this->output;
	}

    /**
     * Set activation function
     * @param IActivationFunction $function [description]
     */
	public function setActivationFunction(IActivationFunction $function){
		$this->activationFunction = $function;
	}

	/**
	 * Returns the square error
	 * @return double
	 */
	public function getSquareError(){
		$counter = 0;
		
		if ($this->expectedOutput != $this->output){
			$counter += $this->expectedOutput - $this->output;
		}
	
		return $counter * $counter;
	}

    /**
     * Return the error
     * @return mixed
     */
	public function getError(){
	    return $this->expectedOutput - $this->output;
    }

    /**
     * @return mixed
     */
    public function getWeights()
    {
        return $this->weights;
    }

    /**
     * @return mixed
     */
    public function getBias()
    {
        return $this->bias;
    }


}