<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/10/16
 * Time: 12:22 PM
 */

namespace App\Utils;

use App\Utils\LUDecomposition;


class MatrixHelpers
{
    static function submatrix ($data, $rowIndexes, $startColumn, $endColumn){

        $X = [];

        for ($i = 0; $i < count($rowIndexes); ++$i)
        {
            for ($j = 0; $j < ($endColumn - $startColumn + 1); ++$j)
            {
                if (($rowIndexes[$i] < 0) || ($rowIndexes[$i] >= count($data)))
                    throw new \Exception("Argument out of range.");

                $X[$i][$j] = $data[$rowIndexes[$i]][$startColumn+$j];
            }
        }
        return $X;
    }

    public static function inverse($A){
        return (new LUDecomposition($A))->inverse();
    }

    /**
     * Gets the Identity matrix of the given size.
     * @param m Number of rows.
     * @param n Number of columns.
     * @return Identity matrix.
     */
    public static function identity($m, $n){
        $A = [];
        for ($i = 0; $i < $m; ++$i) {
            for ($j = 0; $j < $n; ++$j) {
                $A[$i][$j] = ($i == $j ? 1.0 : 0.0);
            }
        }
        return $A;
    }

    static function multiply($A, $B)
    {
        if(count($A[0]) != count($B))
            throw new \Exception("Illegal matrix dimensions.");

        $result = [];

        $n = count($A[0]);
        $m = count($A);
        $p = count($B[0]);

        $Bcolj = [];

        for ($j = 0; $j < $p; ++$j)
        {
            for ($k = 0; $k < $n; ++$k)
                $Bcolj[$k] = $B[$k][$j];

            for ($i = 0; $i < $m; ++$i)
            {
                $Arowi = $A[$i];

                $s = 0;
                for ($k = 0; $k < $n; ++$k)
                    $s += $Arowi[$k] * $Bcolj[$k];

                $result[$i][$j] = $s;
            }
            //echo "$j\n";
        }

        return $result;
    }

    /**
     *
     * @param $A
     */
    public static function transpose($A){
        $t = [];

        for ($i = 0; $i < count($A); ++$i){
            for ($j = 0; $j < count($A[0]); ++$j){
                $t[$j][$i] = $A[$i][$j];
            }
        }

        return $t;
    }

    public static function multiplyByTranspose($A){
        return self::multiply($A, MatrixHelpers::transpose($A));
    }
}