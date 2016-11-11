<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 5:14 PM
 */

namespace App\Genetic;

use App\Utils\Math;

class Chromosome
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
     */
    function initialize($lenght){
        $genes = [];

        for ($i = 0; $i < $lenght; ++$i){
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
}