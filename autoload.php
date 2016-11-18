<?php
/**
 * Created by Cristiano.
 * User: cristiano
 * Date: 10/31/16
 * Time: 12:28 PM
 */

function __autoload($class){
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', '/', $class) . "exampleDE.php";

    echo "$class <--";
    require_once($class);
}