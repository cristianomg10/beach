<?php

use App\GreedyRA\Course;
use App\GreedyRA\LocationClass;
use App\GreedyRA\RelationToCourse;
use App\GreedyRA\Scheduling;
use App\GreedyRA\Subject;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

/*$alunos = [80, 40, 40, 100, 100, 100, 40, 100, 100];
$turmas = ['TGA', 'IALG', 'IALG', 'IComp', 'ISD', 'Calculo I', 'IALG', 'Calculo I', 'Calculo I'];

//$salas = ['PV501', 'PV502', 'PV503', 'PV309', 'PV307', 'DCC01'];
$salas = ['PV501', 'PV301', 'PV302', 'PV309', 'PV307', 'DCC01'];
$capacidade = ['120', '50', '50', '50', '50', '30'];
//$horario = ['07:00', '09:00', '11:00', '13:00'];
$horario = ['19:00', '21:00'];
$dias = ['SEG', 'TER', 'QUA', 'QUI', 'SEX'];

array_multisort($alunos, SORT_DESC, $turmas);
array_multisort($capacidade, SORT_DESC, $salas);

$ocupacao = [];

$v = count($turmas);
//verifica turmas
for ($i = 0; $i < $v; ++$i) {
    //verifica salas
    for ($j = 0; $j < count($salas); ++$j) {
        foreach ($dias as $d){
            //verifica horarios
            if (array_key_exists($i, $turmas) && $alunos[$i] < $capacidade[$j]) {
                foreach ($horario as $h) {
                    if (!isset($ocupacao[$h][$salas[$j]]) && (array_key_exists($i, $turmas) && !($turmas[$i]) == null)) {
                        $ocupacao[$d][$h][$salas[$j]] = $turmas[$i];
                        unset($turmas[$i]);
                        unset($alunos[$i]);
                        ++$i;
                    }
                }
            }
        }
    }
}
*/
//var_dump($ocupacao);

$classes = [
    new LocationClass(150, 'PV501', [5, 3]),
    new LocationClass(150, 'PV502', [5, 3]),
    new LocationClass(60,  'PV301', [5, 3]),
    new LocationClass(60,  'PV302', [5, 3]),
    new LocationClass(60,  'PV309', [5, 3]),
    new LocationClass(60,  'PV307', [5, 3]),
    new LocationClass(30,  'DCC01', [5, 3]),
];

$math = new Course("MAT", []);
$bsi  = new Course("BSI", []);
$admp = new Course("ADP", []);
$dir  = new Course("DIR", []);

$subjects = [
    new Subject('GAE103', new RelationToCourse($bsi, 40, 1)),
    new Subject('GCC224', new RelationToCourse($bsi, 40, 1), 3),
    new Subject('GCC241', new RelationToCourse($bsi, 40, 1), 1),
    new Subject('GCC245', new RelationToCourse($bsi, 40, 1)),
    new Subject('GEX104', [
        new RelationToCourse($bsi, 40, 1),
        new RelationToCourse($math, 40, 2)
    ], 3),
    new Subject('GDE101', new RelationToCourse($math, 40, 1)),
    new Subject('GEX102', new RelationToCourse($math, 40, 1)),
    new Subject('GEX103', new RelationToCourse($math, 40, 1)),
    new Subject('GEX182', new RelationToCourse($math, 40, 1)),
    new Subject('GEX209', new RelationToCourse($math, 40, 1), 1),
    new Subject('GFI103', new RelationToCourse($math, 40, 1)),
    new Subject('GAE105', new RelationToCourse($bsi, 40, 2)),
    new Subject('GCC174', new RelationToCourse($bsi, 40, 2)),
    new Subject('GCC198', new RelationToCourse($bsi, 40, 2)),
    new Subject('GEX252', new RelationToCourse($bsi, 40, 2)),
    new Subject('GEX260', [
        new RelationToCourse($bsi, 40, 2),
        new RelationToCourse($admp, 40, 2)
        ]
    ),

    new Subject('GAE222', new RelationToCourse($admp, 40, 1)),
    new Subject('GAE244', new RelationToCourse($admp, 40, 1), 1),
    new Subject('GCH102', [
        new RelationToCourse($admp, 40, 1),
        new RelationToCourse($dir, 40, 1),
    ]),
    new Subject('GCH104', new RelationToCourse($admp, 40, 1)),
    new Subject('GCH199', new RelationToCourse($admp, 40, 1), 1),
    new Subject('GDE125', new RelationToCourse($admp, 40, 1), 1),
    new Subject('GEX101', new RelationToCourse($admp, 40, 1), 1),

    new Subject('GCH225', new RelationToCourse($dir, 40, 1)),
    new Subject('GCH227', new RelationToCourse($dir, 40, 1)),
    new Subject('GCH238', new RelationToCourse($dir, 40, 1)),
    new Subject('GDI101', new RelationToCourse($dir, 40, 1)),

];

$schedulingTime = [
    '17:00', '19:00', '21:00'
];

$daysOfWeek = [
    'SEG', 'TER', 'QUA', 'QUI', 'SEX'
];

$s = new Scheduling($schedulingTime, $classes, $daysOfWeek);
//var_dump($bsi->getRelationToSubjects());
//$s->generateSchedule($subjects);
$s->generateSchedule($bsi->getRelationToSubjects());
$s->generateSchedule($math->getRelationToSubjects());
$s->generateSchedule($admp->getRelationToSubjects());
$s->generateSchedule($dir->getRelationToSubjects());

//var_dump($s->getSchedule());

echo $s;
