<?php
$topLimit = 1000;

$cities = [
	'TP' => 1000, 
	'SV' => 1000, 
	'LV' => 1000, 
	'BS' => 1000,  
	'SA' => 1000, 
	'PE' => 1000
];

$citiesIndex =
[
	0 => 'TP',
	1 => 'SV',
	2 => 'LV',
	3 => 'BS',
	4 => 'SA',
	5 => 'PE',
];

$matrix = 
[
	#	TP	SV	LV	BS	SA	PE
	'TP' => [$topLimit,	15,	$topLimit,	$topLimit,	$topLimit,	$topLimit],
	'SV' => [15,	$topLimit, 65,	$topLimit,	$topLimit,	80],
	'LV' => [$topLimit,	65, $topLimit,	30,	$topLimit,	40],
	'BS' => [$topLimit,	$topLimit, 30,	$topLimit,	27,	$topLimit],
	'SA' => [$topLimit,	$topLimit, $topLimit,	27,	$topLimit,	20],
	'PE' => [$topLimit,	65, $topLimit,	$topLimit,	20,	$topLimit],
];


function wasVisited($city){
	if ($cities[$city] <> 1000) return true;
	return false;
}

function updateDistances($cities, $citiesIndex, $matrix, $initialValue, $from){
	$cities[$from] = ($cities[$from] > $initialValue ? $initialValue : $cities[$from]);

	for ($i = 0; $i < count($matrix[$from]); ++$i){
		if ($citiesIndex[$i] == $from) continue;
		if (wasVisited($citiesIndex[$i])){
			updateDistances($cities, $citiesIndex, $matrix, $cities[$from] + $matrix[$from][$i], $citiesIndex[$i]);
		}	
	}
}


$from 	= 'TP';
$to 	= 'SA';

updateDistances($cities, $citiesIndex, $matrix, 0, $from);
var_dump($cities);