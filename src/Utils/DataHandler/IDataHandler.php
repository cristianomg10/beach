<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/8/16
 * Time: 9:06 PM
 */

namespace App\Utils\DataHandler;


interface IDataHandler
{
    public function getDataAsMatrix();
    public function getUnlabeledDataForTraining();
    public function getLabelForTraining();
    public function getUnlabeledDataForValidation();
    public function getLabelForValidation();
    public function open($source = '');
    //public function setAttrIndex($attrIndex);
    //public function setValidationRate($rate);
    //public function shuffle();
}