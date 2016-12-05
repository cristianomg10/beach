<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/5/16
 * Time: 12:32 PM
 */

namespace App\Utils\Loggable;


class NotLoggable implements ILoggable
{
    public function write($text){}
}