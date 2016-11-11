<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 4:27 PM
 */

namespace App\Genetic\Operators;


class RandomMutation implements IMutation
{

    public function mutate($individual)
    {
        $point = rand(0, count($individual) - 1);

        $new = $individual;
        $new[$point] = ($individual[$point] == 0 ? 1 : 0);

        return $new;
    }
}