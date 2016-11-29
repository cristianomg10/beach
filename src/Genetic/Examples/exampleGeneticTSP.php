<?php


use App\Genetic\GeneticTSP;
use App\Genetic\Operators\OrderBasedCrossOver;
use App\Genetic\Operators\PermutationChromosome;
use App\Genetic\Operators\RouletteWheelSelection;
use App\Genetic\Operators\SublistReversionMutation;
use App\Genetic\Operators\SublistShuffleMutation;
use App\Genetic\Operators\SwapMutation;
use App\Utils\Functions\ObjectiveFunctions\Function4TSProblemFunction;
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
    [0   , 85.5, 18.8, 29.6, 153 , 138  , 112  ],
    [85.5, 0   , 66.4, 107 , 189 , 173  , 147  ],
    [18.8, 66.4, 0   , 43.3, 167 , 151  , 130  ],
    [29.6, 107 , 43.3, 0   , 125 , 110  , 84.3 ],
    [153 , 189 , 167 , 125 , 0   , 28.5 , 52.2 ],
    [138 , 173 , 151 , 110 , 28.5 , 0   , 57.8 ],
    [112 , 147 , 130 , 84.3, 52.2 , 57.8, 0    ]
]);

$f = new Function4TSProblemFunction($cities);
$tsp = new GeneticTSP(7, 100, 100, 0.3, 0.95, $f, new SublistReversionMutation(), new RouletteWheelSelection(), new OrderBasedCrossOver());
$tsp->run();
echo $tsp->getBest() . "\n";
//echo $tsp->getAverageFitnessPerGeneration() . "\n";
echo $f->compute($tsp->getBest());

