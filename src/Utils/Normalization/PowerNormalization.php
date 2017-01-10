<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 1/6/17
 * Time: 12:56 PM
 */

namespace App\Utils\Normalization;


class PowerNormalization implements INormalization
{

    private $index;
    private $factor;

    function __construct($factor)
    {
        $this->factor = $factor;
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function compute($number)
    {
        return (($number < 0 ? -1: ($number == 0 ? 0 : +1)) * $number) ^ $this->factor;
    }
}