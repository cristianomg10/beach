<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/8/16
 * Time: 9:13 PM
 */

namespace App\DataHandler;


use MathPHP\LinearAlgebra\Matrix;

class CSVDataHandler implements IDataHandler
{
    private $data;
    private $firstLineAsAttrName;
    private $length;
    private $attributesQty;
    private $attrIndex;
    private $label;

    function __construct($firstLineAsAttrName = 0){
        $this->firstLineAsAttrName = $firstLineAsAttrName;
    }

    public function open($source = '')
    {
        $row = 1;
        $saved = [];

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

    public function setAttrIndex($attrIndex)
    {
        $this->attrIndex = $attrIndex;
    }

    public function shuffle()
    {
        $order = range(0, $this->length - 1);
        shuffle($order);

        $newArray = [];
        for ($i = 0; $i < $this->length; ++$i){
            $newArray[$i] = $this->data[$order[$i]];
        }

        $this->data = $newArray;
    }

    public function getUnlabeledData()
    {
        $data = $this->data;
        $dataLabel = [];

        for ($i = 0; $i < $this->length; ++$i){
            $dataLabel[$i] = $data[$i][$this->attrIndex];
            unset($data[$i][$this->attrIndex]);
            $data[$i] = array_values($data[$i]);
        }

        $this->label = new Matrix([$dataLabel]);

        return (new Matrix($data));
    }

    public function getLabelForUnlabeledData(){
        return $this->label;
    }

    public function getDataAsMatrix()
    {
        if (!$this->data) throw new \Exception("The object has no data to retrieve.");
        if (is_array($this->data) && !is_array($this->data)) return (new Matrix([$this->data]))->transpose();

        return (new Matrix($this->data))->transpose();
    }
}