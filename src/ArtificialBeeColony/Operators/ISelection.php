<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/24/16
 * Time: 5:29 PM
 */

namespace App\ArtificialBeeColony\Operators;


interface ISelection
{
    public function select($fitnesses);
}