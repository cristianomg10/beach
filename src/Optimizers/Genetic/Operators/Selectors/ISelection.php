<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/11/16
 * Time: 2:17 PM
 */

namespace App\Optimizers\Genetic\Operators\Selectors;


interface ISelection
{
    public function select($fitnesses);
}