<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/8/16
 * Time: 9:06 PM
 */

namespace App\DataHandler;


interface IDataHandler
{
    public function open($source = '');
    public function setAttrIndex($attrIndex);
    public function shuffle();
    public function getUnlabeledData();
    public function getDataAsMatrix();
}