<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 2/18/17
 * Time: 11:22 AM
 */

namespace App\Utils\Functions\ObjectiveFunctions;


namespace App\Utils\Functions\ObjectiveFunctions;

use App\Classifiers\ANN\SingleLayerPerceptron;
use MathPHP\LinearAlgebra\Matrix;

class SLPFunction implements IObjectiveFunction
{

    private $slp;
    private $input;

    public function compute($individual)
    {
        $result = [];

        if (is_array($individual)) {
            $this->slp->setWeights($individual);
        } else {
            $this->slp->setWeights($individual->getGenes());
        }

        for ($i = 0; $i < $this->input->getN(); ++$i) {
            $result[] = abs($this->expectedOutput->get(0, $i) - $this->slp->run($this->input->getColumn($i)));
        }

        return array_sum($result);
    }

    public function __construct(SingleLayerPerceptron $slp, Matrix $input, Matrix $expectedOutput)
    {
        $this->slp = $slp;
        $this->input = $input;
        $this->expectedOutput = $expectedOutput;
    }
}