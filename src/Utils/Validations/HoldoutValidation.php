<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/25/16
 * Time: 4:02 PM
 */

namespace App\Utils\Validations;


use App\Exceptions\IllegalArgumentException;
use App\Utils\Interfaces\IClassifier;
use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;

class HoldoutValidation implements IValidation
{
    private $data;
    private $percentTraining    = 70;
    private $percentValidation  = 30;
    private $classifier;
    private $labelIndex;
    private $qtyForTraining;
    private $qtyForValidation;
    private $labelForValidation;
    private $length;
    private $label;
    private $lastClassified;

    function __construct(Matrix $data, $labelIndex, $percentValidation = 30){
        $this->data = $data;
        $this->percentValidation = $percentValidation;
        $this->percentTraining = 100 - $percentValidation;
        $this->labelIndex = $labelIndex;
        $this->length = $data->getM();

        $this->setValidationRate($percentValidation);
    }

    private function setValidationRate($rate)
    {
        $this->percentValidation = $rate;
        $this->qtyForTraining = round($this->length * (100 - $rate)/100);
        $this->qtyForValidation = $this->length - $this->qtyForTraining;
    }

    private function getUnlabeledDataForTraining()
    {
        $this->shuffle();
        $data = array_slice($this->data, 0, $this->qtyForTraining, true);
        $dataLabel = [];

        for ($i = 0; $i < $this->qtyForTraining; ++$i){
            $dataLabel[$i] = $data[$i][$this->labelIndex];
            unset($data[$i][$this->labelIndex]);
            $data[$i] = array_values($data[$i]);
        }

        $this->label = new Matrix([$dataLabel]);

        return (new Matrix($data))->transpose();
    }

    private function getLabelForTraining(){
        return $this->label;
    }

    private function getUnlabeledDataForValidation()
    {
        $this->shuffle();
        $data = array_slice($this->data, 0, $this->qtyForValidation, true);
        $dataLabel = [];

        for ($i = 0; $i < $this->qtyForValidation; ++$i){
            $dataLabel[$i] = $data[$i][$this->labelIndex];
            unset($data[$i][$this->labelIndex]);
            $data[$i] = array_values($data[$i]);
        }

        $this->labelForValidation = new Matrix([$dataLabel]);

        return (new Matrix($data))->transpose();
    }

    private function shuffle()
    {
        $order = range(0, $this->length - 1);
        shuffle($order);

        $newArray = [];
        for ($i = 0; $i < $this->length; ++$i){
            $newArray[$i] = $this->data[$order[$i]];
        }

        $this->data = $newArray;
    }

    private function getLabelForValidation()
    {
        return $this->labelForValidation;
    }

    public function getPrecision()
    {
        $predicted = $this->lastClassified->getRow(0);
        $labeled = $this->labelForValidation->getRow(0);

        $count = 0;
        for ($i = 0; $i < count($predicted); ++$i){
            if ($predicted[$i] == $labeled[$i]) ++$count;
        }

        return $count / count($predicted) * 100;
    }

    public function getConfusionMatrix()
    {
        $predicted = $this->lastClassified->getRow(0);
        $labeled = $this->labelForValidation->getRow(0);

        $biggest = count(array_unique($predicted)) > count(array_unique($labeled)) ?
            count(array_unique($predicted)) : count(array_unique($labeled));

        $confusionMatrix = Math::generateRandomMatrix($biggest, $biggest, 0);

        for ($i = 0; $i < count($labeled); ++$i){
            $confusionMatrix[$predicted[$i]][$labeled[$i]]++;
        }

        return new Matrix($confusionMatrix);
    }

    public function setClassifier(IClassifier $classifier)
    {
        $this->classifier = $classifier;
    }

    public function validate()
    {
        if (is_null($this->classifier)) throw new IllegalArgumentException("Classifier not set.");
        $this->classifier->setInput($this->getUnlabeledDataForTraining());
        $this->classifier->setExpectedOutput($this->getLabelForTraining());

        $this->classifier->learn();

        $this->lastClassified = $this->classifier->classify($this->getUnlabeledDataForValidation());
        /*echo "Classified : {$this->lastClassified}\n";
        echo "Correc Lab : {$this->labelForValidation}\n";*/
    }
}