<?php

require_once "ILoggable.php";

/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/27/16
 * Time: 11:59 PM
 */
class ScreenWriterLoggable implements ILoggable
{
    /**
     * @param $text
     */
    public function write($text){
        echo "$text<br>";
    }
}