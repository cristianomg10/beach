<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/1/16
 * Time: 11:10 AM
 */

namespace App\Utils\Loggable;


class TerminalLoggable implements ILoggable
{
    public function write($text){
        echo "$text\n";
    }
}