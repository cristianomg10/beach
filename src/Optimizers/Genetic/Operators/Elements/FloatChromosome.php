<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 1/9/17
 * Time: 8:48 PM
 */

namespace App\Optimizers\Genetic\Operators\Elements;


use App\Utils\Math;

class FloatChromosome implements IChromosome
{
    private $genes = [];
    private $length;

    function getLength()
    {
        return $this->length;
    }

    function initialize($length)
    {
        $genes = [];

        for ($i = 0; $i < $length; ++$i){
            $genes[$i] = Math::getRandomValue();
        }

        $this->length = count($genes);
        $this->genes = $genes;
    }

    function __toString() : string
    {
        $string = "";

        foreach ($this->genes as $g){
            $string .= " $g";
        }

        return "[$string]";
    }

    function getGenes()
    {
        return $this->genes;
    }

    function updateGenes($index, $value)
    {
        $this->genes[$index] = $value;
        $this->length = count($this->genes);
    }

    function getGene($index)
    {
        return $this->genes[$index];
        $this->length = count($this->genes);
    }

    function setGenes($genes)
    {
        $this->genes = $genes;
        $this->length = count($genes);
    }
}