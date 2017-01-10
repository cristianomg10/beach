<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 1/4/17
 * Time: 4:00 PM
 */

namespace App\Utils\Functions\ObjectiveFunctions;


use MathPHP\LinearAlgebra\Matrix;

class InnerProductSumFunction implements IObjectiveFunction
{
    private $input;
    private $expectedOutput;

    function __construct(Matrix $input, Matrix $expectedOutput)
    {
        $this->input = $input;
        $this->expectedOutput = $expectedOutput;
    }

    public function compute($individual)
    {
        $w = new Matrix([$individual]);

        $a = ($w->multiply($this->input))->getMatrix();
        $b = $this->expectedOutput->getMatrix();

        $count = 0;
        for ($i = 0; $i < $this->input->getN(); ++$i){
            if ($b[0][$i] == round($a[0][$i])) ++$count;
        }

        return 1 - ($count / $this->input->getN()) ;
    }
}