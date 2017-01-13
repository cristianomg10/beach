<?php
namespace App\Classifiers\ANN;
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/27/16
 * Time: 9:56 PM
 */

use App\Utils\Functions\ActivationFunctions\RoundedSigmoidalFunction;
use App\Utils\Functions\ActivationFunctions\SigmoidalFunction;
use App\Utils\Functions\ActivationFunctions\StepFunction;
use App\Utils\DataHandler\ISerializable;
use App\Utils\Interfaces\IClassifier;
use App\Utils\Math;
use App\Utils\MatrixHelpers;
use MathPHP\LinearAlgebra\Matrix;
use App\Utils\Functions\ActivationFunctions\IActivationFunction;
use App\Utils\Functions\ActivationFunctions\RoundFunction;

class ExtremeLearningMachine implements ISerializable, IClassifier  {
    private $nHiddenPerceptrons;
    private $hiddenPerceptrons;
    private $activationFunction;
    private $c = 1;
    private $outputPerceptron;
    private $input;
    private $expectedOutput;

    /**
     * @return mixed
     */
    public function getHiddenPerceptrons()
    {
        return $this->hiddenPerceptrons;
    }

    /**
     * @param mixed $hiddenPerceptrons
     */
    public function setHiddenPerceptrons($hiddenPerceptrons)
    {
        $this->hiddenPerceptrons = $hiddenPerceptrons;
    }

    /**
     * @return mixed
     */
    public function getOutputPerceptron()
    {
        return $this->outputPerceptron;
    }

    /**
     * @param mixed $outputPerceptron
     */
    public function setOutputPerceptron($outputPerceptron)
    {
        $this->outputPerceptron = $outputPerceptron;
    }

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
    public function getExpectedOutput()
    {
        return $this->expectedOutput;
    }

    /**
     * @param mixed $expectedOutput
     */
    public function setExpectedOutput(Matrix $expectedOutput)
    {
        $this->expectedOutput = $expectedOutput->transpose();
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

            $v = Math::sumNumberToMatrix(Math::generateRandomVector($this->input->getN(), 2), -1);

            $p->setWeights($v);
            $p->setActivationFunction($this->activationFunction);
            $this->hiddenPerceptrons[$i] = $p;
        }

        // Run perceptrons3
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
        $outputWeights = $this->expectedOutput->transpose();
        $bh = $b->inverse();
        $bh = $bh->multiply($h);
        $outputWeights = $outputWeights->multiply($bh);

        for ($i = 0; $i < $outputWeights->getM(); ++$i){
            $this->outputPerceptron[$i] = new Perceptron();
            $this->outputPerceptron[$i]->setWeights($outputWeights[$i]);
            $this->outputPerceptron[$i]->setBias(0);
            $this->outputPerceptron[$i]->setActivationFunction(new RoundFunction());
        }
    }

    /**
     * @param Matrix $input Parameters where the rows are the registers
     * and the cols are the features.
     * @return Matrix
     */
    public function classify(Matrix $input){
        //$input = $input->transpose();
        $output = [];

        for ($j = 0; $j < $input->getN(); ++$j) {

            $inputForOutput = [];
            for ($i = 0; $i < $this->nHiddenPerceptrons; ++$i) {
                $p = $this->hiddenPerceptrons[$i];
                $p->setInput($input->getColumn($j));
                $inputForOutput[] = $p->calculate();
            }

            //echo $input;
            for ($k = 0; $k < count($this->outputPerceptron); ++$k){
                $this->outputPerceptron[$k]->setInput($inputForOutput);
                $output[$k][] = $this->outputPerceptron[$k]->calculate();
            }

        }

        return new Matrix($output);
    }

    function serialize($file)
    {
        $input = $this->input;
        $expectedOutput = $this->expectedOutput;

        unset($this->input);
        unset($this->expectedOutput);

        $s = serialize($this);
        file_put_contents($file, $s);

        $this->input = $input;
        $this->expectedOutput = $expectedOutput;
    }

    function unserialize($file)
    {
        $s = file_get_contents($file);
        $object = unserialize($s);

        $this->hiddenPerceptrons = $object->getHiddenPerceptrons();
        $this->outputPerceptron = $object->getOutputPerceptron();
        $this->nHiddenPerceptrons = $object->getNHiddenPerceptrons();
    }
}