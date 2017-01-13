<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 1/2/17
 * Time: 3:28 PM
 */

use App\GreedyRA\Course;
use App\GreedyRA\LocationClass;
use App\GreedyRA\RelationToCourse;
use App\GreedyRA\Scheduling;
use App\GreedyRA\Subject;
use App\Utils\DataHandler\CSVDataHandler;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

$csvClasses = new CSVDataHandler();
$csvClasses->open('../Files/classes.csv');
$csvClasses = $csvClasses->getDataAsMatrix()->transpose()->getMatrix();

$csvSubjects = new CSVDataHandler();
$csvSubjects->open('../Files/subjects.csv');
$csvSubjects = $csvSubjects->getDataAsMatrix()->transpose()->getMatrix();

$classes = [];
$subjects = [];
$courses = [];

for ($i = 0; $i < count($csvClasses); ++$i){
    $c = $csvClasses[$i];
    $classes[] = new LocationClass($c[0], $c[1], [$c[2], $c[3]]);
}

for ($i = 0; $i < count($csvSubjects); ++$i){
    $s  = $csvSubjects[$i];

    if (!array_key_exists($s[4], $courses)){
        $courses[$s[4]] = new Course($s[4], []);
    }

    if (!array_key_exists($s[0], $subjects)){
        $subjects[$s[0]] = new Subject($s[0], new RelationToCourse($courses[$s[4]], $s[3], $s[5]), $s[2]);
    } else {
        $subjects[$s[0]]->addCourseRelation(new RelationToCourse($courses[$s[4]], $s[3], $s[5]));
    }

}

$schedulingTime = [
    '17:00', '19:00', '21:00'
];

$daysOfWeek = [
    'SEG', 'TER', 'QUA', 'QUI', 'SEX'
];

$s = new Scheduling($schedulingTime, $classes, $daysOfWeek);

$initialTime = microtime(true);
foreach ($courses as $c){
    $s->generateSchedule($c->getRelationToSubjects());
    echo $c->getName() . "\t\t - Tempo desde o início: " . (microtime(true) - $initialTime) .  " s. \t - Status: OK\n";
    $initialTime = microtime(true);
}

echo $s;