<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/8/16
 * Time: 9:13 PM
 */

namespace App\Utils\DataHandler;


use App\Utils\Exceptions\IllegalArgumentException;
use App\Utils\Math;
use App\Utils\Normalization\INormalization;
use MathPHP\LinearAlgebra\Matrix;

class CSVDataHandler implements IDataHandler
{
    private $data;
    private $firstLineAsAttrName;
    private $length;
    private $attributesQty;
    private $attrIndex;
    private $label;
    private $labelForValidation;
    private $validationRate;
    private $qtyForTraining;
    private $qtyForValidation;
    private $normalization;

    function __construct($firstLineAsAttrName = 0){
        $this->firstLineAsAttrName = $firstLineAsAttrName;
    }

    public function setNormalization(INormalization $normalization){
        $this->normalization = $normalization;
    }

    public function normalize( $index){
        if (!isset($this->data)) throw new IllegalArgumentException("Empty data.");
        if (!isset($this->normalization)) throw new IllegalArgumentException("Normalization method not set.");

        $this->normalization->setIndex($index);
        for ($i = 0; $i < count($this->data); ++$i){
            $this->data[$i][$index] = $this->normalization->compute($this->data[$i][$index]);
        }
    }

    public function open($source = '')
    {
        $row = 1;
        $saved = [];
        $num = 0;

        if (($handle = fopen($source, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                for ($c=0; $c < $num; $c++) {
                    $saved[$row-1][$c] = $data[$c];
                }
                $row++;
            }
            fclose($handle);
        }

        $this->length = $row - 1;
        $this->data = $saved;
        $this->attributesQty = $num;
    }

    public function getDataAsMatrix()
    {
        if (!$this->data) throw new \Exception("The object has no data to retrieve.");
        if (is_array($this->data) && !is_array($this->data)) return (new Matrix([$this->data]))->transpose();

        return (new Matrix($this->data))->transpose();
    }
}