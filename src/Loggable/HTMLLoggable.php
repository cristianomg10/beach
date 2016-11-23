<?php
namespace App\Loggable;

use App\Loggable\ILoggable;

/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/27/16
 * Time: 11:59 PM
 */
class HTMLLoggable implements ILoggable
{
    /**
     * @param $text
     */
    public function write($text){
        echo "$text<br>";
    }
}