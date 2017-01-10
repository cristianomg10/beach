<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 5:14 PM
 */

namespace App\Genetic\Operators\Elements;

use App\Utils\Math;

class BinaryChromosome implements IChromosome
{
    private $genes = [];
    private $length;

    function __construct($genes = []){
        if ($genes != []){
            $this->genes = $genes;
            $this->length = count($genes);
        }
    }

    function getLength(){
        return $this->length;
    }

    /**
     * Initialize randomly
     * @param $length
     */
    function initialize($length){
        $genes = [];

        for ($i = 0; $i < $length; ++$i){
            $genes[$i] = round(Math::getRandomValue());
        }

        $this->length = count($genes);
        $this->genes = $genes;
    }

    function __toString()
    {
        $string = "";

        foreach ($this->genes as $g){
            $string .= " $g";
        }

        return "[$string]";
    }

    function getGenes(){
        return $this->genes;
    }

    /**
     * Update the gene of the index $index.
     * @param $index
     * @param $value
     */
    function updateGenes($index, $value){
        $this->genes[$index] = $value;
        $this->length = count($this->genes);
    }

    /**
     * Get a specific Gene.
     * @param $index
     * @return mixed
     */
    function getGene($index){
        return $this->genes[$index];
        $this->length = count($this->genes);
    }

    function setGenes($genes)
    {
        $this->genes = $genes;
        $this->length = count($genes);
    }
}