<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/25/16
 * Time: 4:13 PM
 */

namespace App\Utils\Interfaces;


interface IOptimizer
{
    public function getBest() : array;
    public function run();
}