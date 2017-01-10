<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 1/6/17
 * Time: 11:21 AM
 */

namespace App\Utils\Normalization;


interface INormalization
{
    public function setIndex($index);
    public function compute($number);
}