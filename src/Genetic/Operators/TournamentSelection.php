<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/28/16
 * Time: 3:08 PM
 */

namespace App\Genetic\Operators;


class TournamentSelection implements ISelection
{
    public function select($fitnesses)
    {
        $count = count($fitnesses);

        return rand(0, $count - 1);
    }
}