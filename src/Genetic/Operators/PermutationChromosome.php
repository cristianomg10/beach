<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/28/16
 * Time: 3:28 PM
 */

namespace App\Genetic\Operators;


class PermutationChromosome implements IChromosome
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

        $genes = range(0, $length);
        shuffle($genes);

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
}