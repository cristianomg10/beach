<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/24/16
 * Time: 5:28 PM
 */

namespace App\Optimizers\ArtificialBeeColony\Operators;


class RouletteWheelSelection implements ISelection
{
    public function select($fitnesses)
    {
        $chances = [];
        $initialValue = 0;

        for ($i = 0; $i < count($fitnesses); ++$i){
            $chances[$i] = $initialValue + $fitnesses[$i];
            $initialValue += $fitnesses[$i];
        }

        $target = rand($fitnesses[0], $fitnesses[$i - 1]);

        for ($i = 0; $i < count($fitnesses); ++$i){
            if ($target < $chances[$i]){
                return $i;
            }
        }
    }
}