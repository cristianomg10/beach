<?php


use App\Genetic\GeneticTSP;
use App\Genetic\Operators\CrossOvers\OrderBasedCrossOver;
use App\Genetic\Operators\Mutators\SublistReversionMutation;
use App\Genetic\Operators\Selectors\RouletteWheelSelection;
use App\Utils\Functions\ObjectiveFunctions\TSProblemFunction;
use MathPHP\LinearAlgebra\Matrix;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../vendor/autoload.php');

/*$pc = new PermutationChromosome();
$pc->initialize(8);
echo $pc . "\n";
$pc = (new SublistReversionMutation())->mutate($pc);
echo $pc;

$pc1 = new PermutationChromosome();
$pc1->initialize(8);

echo "PC: $pc, PC1: $pc1\n";

$cross = new OrderBasedCrossOver();
$ret = $cross->crossOver($pc, $pc1);
echo "Res1: {$ret[0]}, Res2: {$ret[1]}";*/

$cities = new Matrix([
    // TP   LV    SV    VG    SL    CX     LB    BE
    [0   , 85.5, 18.8, 29.6, 153 , 138  , 112 , 39.9],
    [85.5, 0   , 66.4, 107 , 189 , 173  , 147 , 87  ],
    [18.8, 66.4, 0   , 43.3, 167 , 151  , 130 , 21.7],
    [29.6, 107 , 43.3, 0   , 125 , 110  , 84.3, 64.5],
    [153 , 189 , 167 , 125 , 0   , 28.5 , 52.2, 188 ],
    [138 , 173 , 151 , 110 , 28.5, 0    , 57.8, 173 ],
    [112 , 147 , 130 , 84.3, 52.2, 57.8 , 0   , 147 ],
    [39.9, 87  , 21.7, 64.5, 188 , 173  , 147 , 0   ]
]);

$f = new TSProblemFunction($cities);
$tsp = new GeneticTSP(8, 100, 100, 0.3, 0.85, $f, new SublistReversionMutation(), new RouletteWheelSelection(), new OrderBasedCrossOver());
$tsp->run();
echo $tsp->getBest() . "\n";
//echo $tsp->getAverageFitnessPerGeneration() . "\n";
echo $f->compute($tsp->getBest());

