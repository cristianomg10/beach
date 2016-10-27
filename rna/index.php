<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;

require_once 'Perceptron.class.php';
require 'Step.class.php';

# set values
$expectedOutput = new Vector([0, 0, 0, 1]);

$input			= new Matrix([
	[0, 0, 1, 1],
	[0, 1, 0, 1]
]);

$weights		= new Vector([mt_rand() / mt_getrandmax(), mt_rand() / mt_getrandmax()]);
$bias 			= mt_rand() / mt_getrandmax();

# maximum epochs
$maxEpochs  	= 100;
$epoch 	        = 1;
$learningRate	= 0.5;
$err 			= 1000;
$beta           = 0.3;

# initial setting of a neuron
$p = new Perceptron;
$p->setActivationFunction(new Step());

# training
while ($err > 0 /*&& $epoch <= $maxEpochs*/) {
	
	echo "****** STARTING EPOCH: $epoch<br>";

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

echo "Training finished in " . --$epoch . " epochs having error: $err.<br>";
echo "Weights: $weights <br>";
echo "Bias: $bias<br>";

# verify
for ($i = 0; $i < count($input[0]); ++$i){
	$inputTest = [];
	for ($j = 0; $j < count($weights); ++$j){
		$inputTest[] = $input[$j][$i];
	}	

	$p->setInput($inputTest);
    $p->setExpectedOutput($expectedOutput->get($i));
    $p->setBias($bias);
    $p->setWeights($weights->getVector());
    $p->calculate();
	echo "Input test: " . json_encode($inputTest) . " | Output: " . $p->getOutput() . "<br>";
}

