<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/28/16
 * Time: 3:40 PM
 */

namespace App\Genetic\Operators;


interface IChromosome
{
    function getLength();
    function initialize($length);
    function __toString();
    function getGenes();
    function updateGenes($index, $value);
    function getGene($index);
    function setGenes($genes);
}