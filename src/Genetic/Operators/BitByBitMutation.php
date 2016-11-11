<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 3:33 PM
 */

namespace App\Genetic\Operators;


use App\Utils\Math;

class BitByBitMutation implements IMutation
{
    private $rate;

    function __construct($rate = 0.5){
        $this->rate = $rate;
    }

    public function mutate($individual)
    {
        $new = [];
        for ($i = 0; $i < count($individual); ++$i){
            if (Math::getRandomValue() < $this->rate){
                $new[] = ($individual[$i] == 1 ? 0 : 1);
            } else {
                $new[] = $individual[$i];
            }
        }

        return $new;
    }
}