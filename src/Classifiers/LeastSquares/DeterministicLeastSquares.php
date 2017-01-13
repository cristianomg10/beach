<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/1/16
 * Time: 12:32 PM
 */

namespace App\Classifiers\LeastSquares;


use App\Utils\Exceptions\IllegalArgumentException;
use App\Utils\Exceptions\InvalidDataException;
use App\Utils\Interfaces\IClassifier;
use MathPHP\LinearAlgebra\Matrix;

class DeterministicLeastSquares implements IClassifier
{

    private $expectedOutput;
    private $input;
    private $weights;
    private $output;

    public function setInput(Matrix $input)
    {
        $this->input = $input;
        $this->input = $this->addBias2Input($input);
    }

    public function setExpectedOutput(Matrix $expectedOutput)
    {
        $this->expectedOutput = $expectedOutput;
    }

    public function learn()
    {
        $this->weights = [];

        $input = $this->input;
        $inputT = $input->transpose();

        $input = $inputT->multiply($input);
        $input = $input->inverse()->multiply($inputT);
        $input = $input->multiply($this->expectedOutput->transpose());

        $this->weights = $input->transpose()->getMatrix();
    }

    public function classify(Matrix $input)
    {
        $input = $this->addBias2Input($input);

        $weights = (new Matrix($this->weights))->transpose();
        $this->output = $input->multiply($weights);

        return $this->output;
    }

    private function addBias2Input($input){
        if (is_null($this->input)){ throw new InvalidDataException("Input not set."); }

        $array = $input->getMatrix();
        $columns = $input->getN();
        for ($i = 0; $i < $input->getM(); ++$i){
            $array[$i][$columns] = 1;
        }

        $input = new Matrix($array);

        return $input;
    }
}