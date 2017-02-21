<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 2/21/17
 * Time: 2:55 PM
 */

namespace App\Optimizers\Genetic\Operators\Mutators;


use App\Optimizers\Genetic\Operators\Elements\IChromosome;

class InputNodeMutation implements IMutation
{

    private $inputSize;
    private $inputNodesSize;

    public function mutate($individual)
    {
        if (!is_a($individual, IChromosome::class)){
            throw new IllegalArgumentException("Individual is not a Chromosome.");
        }

        $new = $individual->getGenes();
        $node1 = rand(0, $this->inputNodesSize - 1);
        $node2 = rand(0, $this->inputNodesSize - 1);
        while ($node2 == $node1) $node2 = rand(0, $this->inputNodesSize - 1);

        $sublist1 = array_slice($new, $node1 * ($this->inputSize + 1), $this->inputSize + 1);

        for ($i = 0; $i < $this->inputSize + 1; ++$i){
            $new[$node1 * $this->inputSize + $i] = $new[$node2 * $this->inputSize + $i];
        }

        for ($i = 0; $i < $this->inputSize + 1; ++$i){
            $new[$node2 * $this->inputSize + $i] = $sublist1[$i];
        }

        $class = get_class($individual);
        return new $class($new);
    }

    /**
     * InputNodeMutation constructor.
     * @param $inputNodesSize Number of input Nodes
     * @param $inputSize Length of the input
     */
    public function __construct($inputNodesSize, $inputSize)
    {
        $this->inputNodesSize = $inputNodesSize;
        $this->inputSize = $inputSize;
    }
}