<?php
namespace App\Utils;

/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 10/25/16
 * Time: 4:50 PM
 */

class Math
{
    /**
     * Sum a number $number to a $matrix
     * @param $matrix
     * @param $number
     * @return array
     */
    static function sumNumberToMatrix($matrix, $number){
        $newMatrix = [];

        for ($i = 0; $i < count($matrix); ++$i){
            if (is_array($matrix[$i])){
                $newMatrix[$i] = Math::sumNumberToMatrix($matrix[$i], $number);
            }else{
                $newMatrix[] = $matrix[$i] + $number;
            }
        }

        return $newMatrix;
    }

    /**
     * Sum matrices
     * @param $matrixA
     * @param $matrixB
     * @param $rows
     * @param $cols
     * @return array
     */
    static function multiplyMatrices($matrixA, $matrixB, $rows, $cols){
        $newMatrix = [];

        if ($rows > 1){
            for($i = 0; $i < $rows; $i++) {
                for($j = 0; $j < $cols; $j++) {
                    $newMatrix[$i][$j] = $matrixA[$i][$j] + $matrixB[$i][$j];
                }
            }
        } else {
            for($j = 0; $j < $cols; $j++) {
                $newMatrix[$j] = $matrixA[$j] + $matrixB[$j];
            }
        }


        return $newMatrix;
    }

    /**
     *
     * @param $matrix
     * @param $number
     * @return array
     */
    static function multiplyNumberToMatrix($matrix, $number){
        $newMatrix = [];

        for ($i = 0; $i < count($matrix); ++$i){
            if (is_array($matrix[$i])){
                $newMatrix[$i] = Math::multiplyNumberToMatrix($matrix[$i], $number);
            }else{
                $newMatrix[] = $matrix[$i] * $number;
            }
        }

        return $newMatrix;
    }

    /**
     * Method sums matrices
     * @param $matrixA
     * @param $matrixB
     * @param $rows
     * @param $cols
     * @return array
     * @throws Exception
     */
    static function sumMatrices($matrixA, $matrixB, $rows, $cols){
        $newMatrix = [];

        try{
            if ($rows > 1){
                for ($i = 0; $i < $rows; ++$i){
                    for ($j = 0; $j < $cols; ++$j){
                        $newMatrix[$i][$j] = $matrixA[$i][$j] + $matrixB[$i][$j];
                    }
                }
            }else{
                //tested
                for ($i = 0; $i < $cols; ++$i){
                    $newMatrix[$i] = $matrixA[$i] + $matrixB[$i];
                }
            }

            return $newMatrix;

        } catch (\Exception $e){
            throw $e;
        }

    }

    /**
     * Generate random vector
     * @param $length of the vector
     * @param float|number $coefficient number to be multiplied to the random number.
     * @return array
     */
    static function generateRandomVector($length, $coefficient=1.0){
        $vector = [];

        for ($i = 0; $i < $length; ++$i){
            $vector[$i] = Math::getRandomValue($coefficient);
        }

        return $vector;
    }

    /**
     * @param $m
     * @param $n
     * @param $coefficient
     * @return array
     */
    static function generateRandomMatrix($m, $n, $coefficient=1.0){
        $matrix = [];

        for ($i = 0; $i < $m; ++$i){
            for ($j = 0; $j < $n; ++$j){
                $matrix[$i][$j] = Math::getRandomValue($coefficient);
            }
        }

        return $matrix;
    }

    /**
     * @param float $coefficient
     * @return float|int
     */
    static function getRandomValue($coefficient = 1.0){
        srand(7);
        return (mt_rand() / mt_getrandmax()) * ($coefficient);
    }

    static function min($i, $j){
        if ($i < $j) return $i;
        return $j;
    }
}