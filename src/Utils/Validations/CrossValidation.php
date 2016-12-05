<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 11/26/16
 * Time: 8:18 PM
 */

namespace App\Utils\Validations;


use App\Utils\Interfaces\IClassifier;
use App\Utils\Math;
use MathPHP\LinearAlgebra\Matrix;

class CrossValidation implements IValidation
{

    private $classifier;
    private $data;
    private $labelIndex;
    private $k;
    private $lastClassified;
    private $foldings;
    private $lFoldings;
    private $length;
    private $predicted;
    private $joinedPredicted;
    private $labeled;
    private $joinedLabeled;

    use MultiClassFunctions;

    function __construct(Matrix $data, $labelIndex, $k = 10)
    {
        $this->data = $data;
        $this->labelIndex = $labelIndex;
        $this->k = $k;
        $this->length = $data->getM();

        $this->shuffle();
        $pieceLength = floor($this->length / $k);

        $label = [];
        for ($i = 0; $i < $k; ++$i){
            $qty = ($i == $k -1 ? $this->length - ($i * $pieceLength) : $pieceLength );
            $this->foldings[$i] = array_slice($this->data, $i * $pieceLength, $qty, false);

            for ($j = 0; $j < count($this->foldings[$i]); ++$j){
                $label[$i][$j] = $this->foldings[$i][$j][$this->labelIndex];
                unset($this->foldings[$i][$j][$this->labelIndex]);
            }
            $this->lFoldings[$i] = $label[$i];
        }

    }

    public function setClassifier(IClassifier $classifier)
    {
        $this->classifier = $classifier;
    }

    public function getPrecision()
    {
        $count = [];
        $precision = [];

        for ($i = 0; $i < count($this->predicted); ++$i){
            $predicted = $this->predicted[$i]->getRow(0);
            $labeled = $this->lFoldings[$i];

            $count[$i] = 0;
            for ($j = 0; $j < count($predicted); ++$j){
                if ($predicted[$j] == $labeled[$j]) ++$count[$i];
            }

            $precision[$i] = $count[$i] / count($predicted) * 100;
        }

        $stdDev = Math::standardDeviation($precision);
        $mean = array_sum($precision) / count($precision);

        return "$mean +/- $stdDev";
    }

    public function getConfusionMatrix()
    {
        $biggest = count(array_unique($this->joinedPredicted)) > count(array_unique($this->joinedLabeled)) ?
            count(array_unique($this->joinedPredicted)) : count(array_unique($this->joinedLabeled));

        $confusionMatrix = Math::generateRandomMatrix($biggest, $biggest, 0);

        $predicted = $this->joinedPredicted;
        $labeled = $this->joinedLabeled;
        for ($i = 0; $i < count($predicted); ++$i){
            $confusionMatrix["{$predicted[$i]}"]["{$labeled[$i]}"]++;
        }

        return new Matrix($confusionMatrix);
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

    public function validate()
    {
        $this->predicted = [];
        $this->joinedPredicted = [];
        $this->joinedLabeled = [];

        for ($i = 0; $i < $this->k; ++$i){

            $data = [];
            $label = [];
            for ($j = 0; $j < $this->k; ++$j){
                if ($i != $j) {
                    $data = array_merge($data, $this->foldings[$j]);
                    $label = array_merge($label, $this->lFoldings[$j]);
                }
            }

            if (is_null($this->classifier)) throw new IllegalArgumentException("Classifier not set.");
            $this->classifier->setInput((new Matrix($data))->transpose());
            $this->classifier->setExpectedOutput((new Matrix([$label])));

            $this->classifier->learn();

            $this->predicted[$i] = $this->classifier->classify((new Matrix($this->foldings[$i]))->transpose());

            $this->joinedLabeled = array_merge($this->joinedLabeled, $this->lFoldings[$i]);
            $this->joinedPredicted = array_merge($this->joinedPredicted, $this->predicted[$i]->getRow(0));
        }
    }
}