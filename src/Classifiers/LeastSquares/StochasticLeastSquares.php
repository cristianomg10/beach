<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/1/16
 * Time: 5:04 PM
 */

namespace App\Classifiers\LeastSquares;


use App\Utils\Exceptions\InvalidDataException;
use App\Utils\Interfaces\IClassifier;
use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;

class StochasticLeastSquares implements IClassifier
{
    private $input;
    private $output;
    private $expectedOutput;
    private $maxEpochs;
    private $momentumRate;
    private $learningRate;
    private $minimumError;
    private $weights;
    private $bias;

    function __construct($maxEpochs, $minimumError = 0, $learningRate = 0.4, $momentumRate = 0.2)
    {
        $this->maxEpochs = $maxEpochs;
        $this->learningRate = $learningRate;
        $this->momentumRate = $momentumRate;
        $this->minimumError = $minimumError;
    }

    public function setInput(Matrix $input)
    {
        $this->input = $input;//$this->addBias2Input($input);
    }

    public function setExpectedOutput(Matrix $expectedOutput)
    {
        $this->expectedOutput = $expectedOutput;
    }

    public function learn()
    {
        $error = 0;
        $this->weights = [];
        $currentWeights = [];

        $nextWeights = [Math::generateRandomVector($this->input->getM())];
        $errorPerEpoch = [];
        $i = 0;

        while ($i < $this->maxEpochs){
            $e2 = 0;
            for ($j = 0; $j < $this->input->getN(); ++$j){
                $currentWeights = $nextWeights[0];

                $matrixCW   = new Matrix([$currentWeights]);
                $row        = new Matrix([$this->input->getColumn($j)]);
                $expOut     = new Matrix([$this->expectedOutput->getColumn($j)]);
                $value      = $matrixCW->multiply($row->transpose())->get(0,0);

                $error      = $expOut->get(0,0) - $value;
                $errorC     = $row->scalarMultiply($error);

                $e2         += $error ** 2;
                $value      = $errorC->scalarMultiply($this->learningRate)->add($errorC->scalarMultiply($this->momentumRate));

                $nextWeights = $matrixCW->add($value)->getMatrix();
            }

            $errorPerEpoch[$i] = $e2;
            ++$i;
        }

        $this->weights = $nextWeights;
    }

    public function classify(Matrix $input)
    {
        //$input = $input;

        $weights = (new Matrix($this->weights));
        $this->output = $weights->multiply($input);
        return $this->output;
    }

    private function addBias2Input($input){
        if (is_null($input)){ throw new InvalidDataException("Input not set."); }

        $array = $input->getMatrix();
        $columns = $input->getN();
        for ($i = 0; $i < $input->getM(); ++$i){
            $array[$i][$columns] = 1;
        }

        $input = new Matrix($array);

        return $input;
    }
}