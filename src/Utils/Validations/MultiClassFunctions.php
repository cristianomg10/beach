<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 12/5/16
 * Time: 5:55 PM
 */

namespace App\Utils\Validations;


use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;

trait MultiClassFunctions
{
    private function classAgainstOthers(Matrix $classification){
        $classification = $classification->getMatrix();
        $unique         = array_unique($classification[0]);
        $countUnique    = count($unique);
        $count          = count($classification[0]);
        $newClass       = Math::generateRandomMatrix($countUnique, $count, 0);

        for ($i = 0; $i < $countUnique; ++$i){
            for ($j = 0; $j < $count; ++$j){
                $newClass[$i][$j] = ($classification[0][$j] == $i ? 1 : 0);
            }
        }

        return new Matrix($newClass);
    }

    private function againstOther2Class(Matrix $matrix){
        $newArray = Math::generateRandomVector($matrix->getN(), 0);
        $array = $matrix->getMatrix();

        for ($i = 0; $i < count($array); ++$i){
            for ($j = 0; $j < count($array[$i]); ++$j){
                $newArray[$j] = ($array[$i][$j] == 1 ? $i : $newArray[$j]);
            }
        }

        return new Matrix([$newArray]);
    }
}