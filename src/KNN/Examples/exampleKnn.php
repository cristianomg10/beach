<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 1/12/17
 * Time: 3:50 PM
 */

use App\KNN\KNN;
use App\Utils\DataHandler\CSVDataHandler;
use App\Utils\Normalization\Normalization;
use App\Utils\Validations\HoldoutValidation;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

$data = new CSVDataHandler(0);
$data->open('../../Utils/DataHandler/Datasets/iris.csv');
$n  = new Normalization($data->getDataAsMatrix(), -1, 1);

$d = $data->getDataAsMatrix()->transpose();
$ho = new HoldoutValidation($data->getDataAsMatrix()->transpose(), 4, 10);

$k = new KNN(3);
$k->setInput($ho->getUnlabeledDataForTraining());
$k->setExpectedOutput($ho->getLabelForTraining());
$k->learn();
echo $k->classify($ho->getUnlabeledDataForValidation());
echo "\n";
echo $ho->getLabelForValidation();