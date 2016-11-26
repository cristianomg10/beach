<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/9/16
 * Time: 5:58 PM
 */

namespace App\Utils\DataHandler;


interface ISerializable
{
    function serialize($file);
    function unserialize($file);
}