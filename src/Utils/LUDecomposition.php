<?php

namespace App\Utils;
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/10/16
 * Time: 11:47 AM
 */




   /** LU Decomposition.
   <P>
   For an m-by-n matrix A with m >= n, the LU decomposition is an m-by-n
   unit lower triangular matrix L, an n-by-n upper triangular matrix U,
   and a permutation vector piv of length m so that A(piv,:) = L*U.
   If m < n, then L is m-by-m and U is m-by-n.
   <P>
   The LU decompostion with pivoting always exists, even if the matrix is
   singular, so the constructor will never fail.  The primary use of the
   LU decomposition is in the solution of square systems of simultaneous
   linear equations.  This will fail if isNonsingular() returns false.
    */

class LUDecomposition {

    /** Array for internal storage of decomposition.
    @serial internal array storage.
     */
    private $LU;

   /** Row and column dimensions, and pivot sign.
   @serial column dimension.
   @serial row dimension.
   @serial pivot sign.
    */
   private $m, $n, $pivsign; 

   /** Internal storage of pivot vector.
   @serial pivot vector.
    */
   private $piv;
   
   /**
    * Initializes a new instance of the LUDecomposition class.
    * @param matrix Matrix.
    */
   
   function __construct (array $matrix) {

        // Use a "left-looking", dot-product, Crout/Doolittle algorithm.

        $LU = $matrix;
        $m = count($matrix);
        $n = count($matrix[0]);
        $piv = [];
        
        for ($i = 0; $i < $m; ++$i)
            $piv[$i] = $i;
      
        $pivsign = 1;
        $LUrowi = [];
        $LUcolj = [];

        // Outer loop.
        for ($j = 0; $j < $n; ++$j) {

            // Make a copy of the j-th column to localize references.

            for ($i = 0; $i < $m; ++$i) {
                $LUcolj[$i] = $LU[$i][$j];
            }

         // Apply previous transformations.
    
             for ($i = 0; $i < $m; ++$i) {
                $LUrowi = $LU[$i];
    
                // Most of the time is spent in the following dot product.
    
                $kmax = Math::min($i,$j);
                $s = 0.0;
                for ($k = 0; $k < $kmax; ++$k) {
                    $s += $LUrowi[$k] * $LUcolj[$k];
                }
    
                $LUcolj[$i] -= $s;
                 $LUrowi[$j] = $LUcolj[$i];
             }
   
         // Find pivot and exchange if necessary.
         $p = $j;
         for ($i = $j+1; $i < $m; ++$i) {
                if (abs($LUcolj[$i]) > abs($LUcolj[$p])) {
                    $p = $i;
                }
            }
         if ($p != $j) {
             for ($k = 0; $k < $n; ++$k) {
                 $t = $LU[$p][$k]; 
                 $LU[$p][$k] = $LU[$j][$k]; 
                 $LU[$j][$k] = $t;
            }
            $k = $piv[$p]; 
             $piv[$p] = $piv[$j]; 
             $piv[$j] = $k;
                $pivsign = -$pivsign;
         }

         // Compute multipliers.
         
            if ($j < $m && $LU[$j][$j] != 0.0) {
                for ($i = $j+1; $i < $m; ++$i) {
                    $LU[$i][$j] /= $LU[$j][$j];
                }
            }
        }
        $this->piv = $piv;
        $this->pivsign = $pivsign;
        $this->LU = $LU = $matrix;
        $this->m = $m;
        $this->n = $n;
    }

   /**
    * Check if the matrix is non singular.
    * @return True if U, and hence A, is nonsingular.
    */
   public function isNonsingular () {
      for ($j = 0; $j < $this->n; ++$j) {
        if ($this->LU[$j][$j] == 0)
            return false;
    }
      return true;
   }
   
   /**
    * Matrix inverse or pseudoinverse.
    * @return Matrix inverse.
    */
   public function inverse(){
       return $this->solve(MatrixHelpers::identity($this->m, $this->m));
   }
   
   /**
    * Get the Lower triangular factor.
    * @return L.
    */
    public function getL () {
        $X = [];
        $L = $X;
        for ($i = 0; $i < $this->m; ++$i) {
            for ($j = 0; $j < $this->n; ++$j) {
                if ($i > $j) {
                    $L[$i][$j] = $this->LU[$i][$j];
                } else if ($i == $j) {
                    $L[$i][$j] = 1.0;
                } else {
                    $L[$i][$j] = 0.0;
                }
            }
        }
        return $X;
    }

   /**
    * Get the Upper triangular factor.
    * @return U.
    */
    function getU () {
        $X = [];
        $U = $X;
        for ($i = 0; $i < $this->n; ++$i) {
            for ($j = 0; $j < $this->n; ++$j) {
                if ($i <= $j) {
                    $U[$i][$j] = $this->LU[$i][$j];
                } else {
                    $U[$i][$j] = 0.0;
                }
            }
        }
        return $X;
   }

   /**
    * Get the pivot permutation vector.
    * @return Pivot.
    */
   public function getPivot () {
        $p = [];

       for ($i = 0; $i < $this->m; ++$i) {
            $p[$i] = $this->piv[$i];
        }
        return $p;
   }

   /** Return pivot permutation vector as a one-dimensional $array
   @return     (double) piv
    */

   /**
    * Get the pivot permutation vector as $type.
    * @return Pivot.
    */
   public function getDoublePivot () {
        $vals = [];

       for ($i = 0; $i < $this->m; ++$i) {
            $vals[$i] = $this->piv[$i];
        }

        return $vals;
   }

   /**
    * Calculate the determinant.
    * @return Determinant.
    */
   public function determinant () {
        if ($this->m != $this->n) {
            throw new Exception("Matrix must be square.");
        }
        $d = $this->pivsign;

        for ($j = 0; $j < $this->n; ++$j) {
            $d *= $this->LU[$j][$j];
        }

        return $d;
   }

   /**
    * Solve A*X = B
    * @param B A Matrix with as many rows as A and any number of columns.
    * @return X so that L*U*X = B(piv,:)
    * @exception  IllegalArgumentException Matrix row dimensions must agree.
    * @exception  RuntimeException  Matrix is singular.
    */
   public function solve ($B) {
        if (count($B) != $this->m) {
            throw new \Exception("Matrix row dimensions must agree.");
        }
        if (!$this->isNonsingular()) {
            throw new \Exception("Matrix is singular.");
        }

        // Copy right hand side with pivoting
        $nx = count($B[0]);
        $X = MatrixHelpers::submatrix($B, $this->piv, 0, $nx - 1);

      // Solve L*Y = B(piv,:)
        for ($k = 0; $k < $this->n; ++$k) {
            for ($i = $k+1; $i < $this->n; ++$i) {
                for ($j = 0; $j < $nx; ++$j) {
                    $X[$i][$j] -= $X[$k][$j] * $this->LU[$i][$k];
                }
            }
        }

        // Solve U*X = Y;
        for ($k = $this->n-1; $k >= 0; --$k) {
            for ($j = 0; $j < $nx; ++$j) {
                $X[$k][$j] /= $this->LU[$k][$k];
            }

            for ($i = 0; $i < $k; ++$i) {
                for ($j = 0; $j < $nx; ++$j) {
                    $X[$i][$j] -= $X[$k][$j] * $this->LU[$i][$k];
                }
            }
        }
      return $X;
   }
}