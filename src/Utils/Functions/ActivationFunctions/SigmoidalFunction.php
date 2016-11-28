<?php

namespace App\Utils\Functions\ActivationFunctions;

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;

/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/25/16
 * Time: 3:57 PM
 */
class SigmoidalFunction implements IActivationFunction
{
    private $step;

    function __construct($step = 1)
    {
        $this->step = $step;
    }

    private function formula($value){
        $calc = (1 + M_E ^ (- $value));
        if ($calc == 0){
            return 0.0000000000001;
        }
        return (1 / $calc) * $this->step;
    }

    private function derivativeFormula($value){
        return (M_E ^ ($value))/(((M_E ^ ($value)) + 1) ** 2);
    }

    public function compute($value)
    {
        if ($value instanceof Matrix){
            $ret = [];

            for ($i = 0; $i < $value->getM(); ++$i){
                for ($j = 0; $j < $value->getN(); ++$j){
                    $ret[$i][$j] = $this->formula($value->get($i, $j));
                }
            }

            $ret = new Matrix($ret);
        } else if ($value instanceof Vector){
            $ret = [];

            for ($i = 0; $i < $value->getN(); ++$i){
                $ret[$i] = $this->formula($value->get($i));
            }

            $ret = new Vector($ret);
        } else {
            $ret = $this->formula($value);
        }

        return $ret;
    }

    public function derivative($value){
        if ($value instanceof Matrix){
            $ret = [];

            for ($i = 0; $i < $value->getM(); ++$i){
                for ($j = 0; $j < $value->getN(); ++$j){
                    $ret[$i][$j] = $this->derivativeFormula($value->get($i, $j));
                }
            }

            $ret = new Matrix($ret);
        } else if ($value instanceof Vector){
            $ret = [];

            for ($i = 0; $i < $value->getN(); ++$i){
                $ret[$i] = $this->derivativeFormula($this->get($i));
            }

            $ret = new Vector($ret);
        } else {
            $ret = $this->derivativeFormula($value);
        }

        return $ret;
    }
}