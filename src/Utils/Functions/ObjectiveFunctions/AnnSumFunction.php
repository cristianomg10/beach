<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 1/6/17
 * Time: 2:11 PM
 */

namespace App\Utils\Functions\ObjectiveFunctions;


use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;

class AnnSumFunction implements IObjectiveFunction
{

    private $numberOfHiddenElements;
    private $numberOfAttr;
    private $expectedOutput;
    private $input;

    function __construct($numberOfAttr, $numberOfHiddenElements, Matrix $input, Matrix $expectedOutput)
    {
        $this->numberOfAttr = $numberOfAttr;
        $this->numberOfHiddenElements = $numberOfHiddenElements;
        $this->input = $input;
        $this->expectedOutput = $expectedOutput;
    }

    public function compute($individual)
    {
        $wm = [];
        for ($i = 0; $i < $this->numberOfHiddenElements; ++$i){
            for ($j = 0; $j < $this->numberOfAttr; ++$j){
                $wm[$i][$j] = $individual[$i+$j];
            }
        }

        $w = new Matrix($wm);

        $w2 = [];
        $start = $this->numberOfHiddenElements * $this->numberOfAttr;
        for ($i = $start; $i < $start + $this->numberOfHiddenElements; ++$i){
            $w2[$i - $start] = $individual[$i];
        }

        $w2 = new Matrix([$w2]);
        $a = $w2->multiply($w->multiply($this->input))->getMatrix();
        $b = $this->expectedOutput->getMatrix();

        $c = Math::generateRandomVector(count($b[0]), 0);

        for ($i = 0; $i < count($a); ++$i){
            for ($j = 0; $j < count($b[0]); ++$j){
                $c[$j] += $a[$i][$j];
            }
        }

        $count = 0;
        for ($i = 0; $i < $this->input->getN(); ++$i){
            if ($b[0][$i] == round($c[$i])) ++$count;
        }

        return 1 - ($count / $this->input->getN()) ;
    }
}