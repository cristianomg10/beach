<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 2:15 PM
 */

namespace App\Genetic\Operators;


interface ICrossOver
{
    public function crossOver($individual1, $individual2);
}