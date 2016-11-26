<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/8/16
 * Time: 9:13 PM
 */

namespace App\Utils\DataHandler;


use App\Utils\Math;
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

    function __construct($firstLineAsAttrName = 0){
        $this->firstLineAsAttrName = $firstLineAsAttrName;
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

    public function getUnlabeledDataForTraining()
    {
        $this->shuffle();
        $data = array_slice($this->data, 0, $this->qtyForTraining, true);
        $dataLabel = [];

        for ($i = 0; $i < $this->qtyForTraining; ++$i){
            $dataLabel[$i] = $data[$i][$this->attrIndex];
            unset($data[$i][$this->attrIndex]);
            $data[$i] = array_values($data[$i]);
        }

        $this->label = new Matrix([$dataLabel]);

        return (new Matrix($data))->transpose();
    }

    public function getLabelForTraining(){
        return $this->label;
    }

    public function getDataAsMatrix()
    {
        if (!$this->data) throw new \Exception("The object has no data to retrieve.");
        if (is_array($this->data) && !is_array($this->data)) return (new Matrix([$this->data]))->transpose();

        return (new Matrix($this->data))->transpose();
    }

    public function getUnlabeledDataForValidation()
    {
        $this->shuffle();
        $data = array_slice($this->data, 0, $this->qtyForValidation, true);
        $dataLabel = [];

        for ($i = 0; $i < $this->qtyForValidation; ++$i){
            $dataLabel[$i] = $data[$i][$this->attrIndex];
            unset($data[$i][$this->attrIndex]);
            $data[$i] = array_values($data[$i]);
        }

        $this->labelForValidation = new Matrix([$dataLabel]);

        return (new Matrix($data))->transpose();
    }

    public function getLabelForValidation()
    {
        return $this->labelForValidation;
    }

    /**
     * @param $rate in %
     */
    public function setValidationRate($rate)
    {
        $this->validationRate = $rate;
        $this->qtyForTraining = round($this->length * (100 - $rate)/100);
        $this->qtyForValidation = $this->length - $this->qtyForTraining;
    }
}