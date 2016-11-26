<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/25/16
 * Time: 3:56 PM
 */

namespace App\Utils\Validations;


use App\Utils\Interfaces\IClassifier;

interface IValidation
{
    public function setClassifier(IClassifier $classifier);
    public function getPrecision();
    public function getConfusionMatrix();
    public function validate();
}