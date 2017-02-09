<?php

use App\Optimizers\Genetic\GeneticTSP;
use App\Optimizers\Genetic\Operators\CrossOvers\OrderBasedCrossOver;
use App\Optimizers\Genetic\Operators\Elements\PermutationChromosome;
use App\Optimizers\Genetic\Operators\Mutators\SublistReversionMutation;
use App\Optimizers\Genetic\Operators\Mutators\SublistShuffleMutation;
use App\Optimizers\Genetic\Operators\Mutators\SwapMutation;
use App\Optimizers\Genetic\Operators\Mutators\WholeReversionMutation;
use App\Optimizers\Genetic\Operators\Selectors\RouletteWheelSelection;
use App\Optimizers\Genetic\Operators\Selectors\TournamentSelection;
use App\Utils\Functions\ObjectiveFunctions\TSProblemFunction;
use MathPHP\LinearAlgebra\Matrix;

error_reporting(E_ALL);
ini_set('memory_limit', '4096M');
ini_set('display_errors', 'On');
ini_set('max_execution_time', -1);
require_once(__DIR__ . '/../../../../vendor/autoload.php');

/*
 * - Possíveis mutações
 * Sublist reversion
 * Sublist shuffle
 * Swap
 *
 * - Possíveis seleções
 * Roulette Wheel
 * Tournament
 *
 * - Possíveis crossovers
 * OrderBased
 */

function fatorial($a){
    if ($a == 1) return $a;
    return $a * fatorial($a - 1);
}


$mutations = array(
    'SR' => new SublistReversionMutation(),
    #'SS' => new SublistShuffleMutation(),
    #'SW' => new SwapMutation()
    #'WR' => new WholeReversionMutation()
);

$selections = [
    //'R' => new RouletteWheelSelection(),
    'T' => new TournamentSelection()
];

$elitismo = [
  0, 1
];

$crossovers = [
    'O' => new OrderBasedCrossOver()
];

$populationSize = [
    /*25, */50 #, 75, 100, 125, 150, 200
];

$probabilityMutation = [
    0.1, /*0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 0.95*/
];

$probabilityCrossOver = [
    /*0.1, 0.2, 0.3, 0.4, 0.5, 0.6,*/ 0.7#, 0.8, 0.9, 0.95
];

$generations = [
    /*50, 75, 100 , 125,*/ 150, #200
];

//585.4 [ 4 5 8 3 0 7 2 1 10 9 6]
// SL CX TC VG TP BE SV LV BS PE LB
//4 8 1 10 9 2 7 3 0 6 5
// SL TC LV BS PE SV BE VG TP LB CX

// 4 8 1 10 9 2 7 3 0 6 5
// SL TC LV BS PE SV BE VG TP LB CX

//9 10 6 4 5 8 3 0 2 7 1
// PE BS LB SL CX TC VG TP SV BE LV

$cities = new Matrix([
    // TP   LV    SV    VG    SL    CX     LB    BE   TC    PE    BS
    [0   , 85.5, 18.8, 29.6, 153 , 138  , 112 , 39.9, 62.9, 80.6, 130 ],
    [85.5, 0   , 66.4, 107 , 189 , 173  , 147 , 87  , 88.2, 28.9, 47.4],
    [18.8, 66.4, 0   , 43.3, 167 , 151  , 130 , 21.7, 80.6, 69.4, 119 ],
    [29.6, 107 , 43.3, 0   , 125 , 110  , 84.3, 64.5, 33.1, 101 , 151 ],
    [153 , 189 , 167 , 125 , 0   , 28.5 , 52.2, 188 , 91.1, 162 , 232 ],
    [138 , 173 , 151 , 110 , 28.5, 0    , 57.8, 173 , 75.4, 146 , 216 ],
    [112 , 147 , 130 , 84.3, 52.2, 57.8 , 0   , 147 , 49.5, 141 , 183 ],
    [39.9, 87  , 21.7, 64.5, 188 , 173  , 147 , 0   , 97.2, 81.3, 131 ],
    [62.9, 88.2, 80.6, 33.1, 91.1, 75.4 , 49.5, 97.2, 0   , 83.5, 133 ],
    [80.6, 28.9, 69.4, 101 , 162 , 146  , 141 , 81.3, 83.5, 0   , 50.2],
    [130 , 47.4, 119 , 151 , 232 , 216  , 183 , 131, 133, 50.2  , 0   ],
]);

/*$cities = new Matrix([
    // TP   LV    SV    VG    SL    CX     LB    BE   TC    PE    BS
    [0   , INF , 18.8, 29.6, INF , INF  , INF , INF  , INF , INF , INF ],
    [INF , 0   , 66.4, 107 , INF , INF  , INF , INF  , 88.2, 28.9, 47.4],
    [18.8, 66.4, 0   , INF , INF , INF  , INF , 21.7 , INF , 69.4, INF ],
    [29.6, 107 , INF , 0   , INF , INF  , INF , INF  , 33.1, 101 , INF ],
    [INF , INF , INF , INF , 0   , 28.5 , 52.2, INF  , 91.1, INF , INF ],
    [INF , INF , INF , INF , 28.5, 0    , 57.8, 173  , 75.4, 146 , 216 ],
    [INF , INF , INF , INF , 52.2, 57.8 , 0   , INF  , 49.5, INF , INF ],
    [INF , INF , 21.7, INF , INF , 173  , INF , 0    , INF , INF , INF ],
    [INF , 88.2, INF , 33.1, 91.1, 75.4 , 49.5, INF  , 0   , 83.5, INF ],
    [INF , 28.9, 69.4, 101 , INF , 146  , INF , INF  , 83.5, 0   , 50.2],
    [INF , 47.4, INF , INF , INF , 216  , INF , INF  , INF , 50.2  , 0 ],
]);*/

$models = [
    'TOTAL' => new Matrix([
        // TP   LV    SV    VG    SL    CX     LB    BE   TC    PE    BS
        [0   , 85.5, 18.8, 29.6, 153 , 138  , 112 , 39.9, 62.9, 80.6, 130 ],
        [85.5, 0   , 66.4, 107 , 189 , 173  , 147 , 87  , 88.2, 28.9, 47.4],
        [18.8, 66.4, 0   , 43.3, 167 , 151  , 130 , 21.7, 80.6, 69.4, 119 ],
        [29.6, 107 , 43.3, 0   , 125 , 110  , 84.3, 64.5, 33.1, 101 , 151 ],
        [153 , 189 , 167 , 125 , 0   , 28.5 , 52.2, 188 , 91.1, 162 , 232 ],
        [138 , 173 , 151 , 110 , 28.5, 0    , 57.8, 173 , 75.4, 146 , 216 ],
        [112 , 147 , 130 , 84.3, 52.2, 57.8 , 0   , 147 , 49.5, 141 , 183 ],
        [39.9, 87  , 21.7, 64.5, 188 , 173  , 147 , 0   , 97.2, 81.3, 131 ],
        [62.9, 88.2, 80.6, 33.1, 91.1, 75.4 , 49.5, 97.2, 0   , 83.5, 133 ],
        [80.6, 28.9, 69.4, 101 , 162 , 146  , 141 , 81.3, 83.5, 0   , 50.2],
        [130 , 47.4, 119 , 151 , 232 , 216  , 183 , 131, 133, 50.2  , 0   ],
    ]),

    /*'5' => new Matrix([
        // TP   LV    SV    VG    SL
        [0   , 85.5, 18.8, 29.6, 153],
        [85.5, 0   , 66.4, 107 , 189],
        [18.8, 66.4, 0   , 43.3, 167],
        [29.6, 107 , 43.3, 0   , 125],
        [153 , 189 , 167 , 125 , 0  ]
    ]),

    '6' => new Matrix([
        // TP   LV    SV    VG    SL    CX
        [0   , 85.5, 18.8, 29.6, 153 , 138 ],
        [85.5, 0   , 66.4, 107 , 189 , 173 ],
        [18.8, 66.4, 0   , 43.3, 167 , 151 ],
        [29.6, 107 , 43.3, 0   , 125 , 110 ],
        [153 , 189 , 167 , 125 , 0   , 28.5],
        [138 , 173 , 151 , 110 , 28.5, 0   ]
    ]),

    '7' => new Matrix([
        // TP   LV    SV    VG    SL    CX     LB    BE   TC    PE    BS
        [0   , 85.5, 18.8, 29.6, 153 , 138  , 112 ],
        [85.5, 0   , 66.4, 107 , 189 , 173  , 147 ],
        [18.8, 66.4, 0   , 43.3, 167 , 151  , 130 ],
        [29.6, 107 , 43.3, 0   , 125 , 110  , 84.3],
        [153 , 189 , 167 , 125 , 0   , 28.5 , 52.2],
        [138 , 173 , 151 , 110 , 28.5, 0    , 57.8],
        [112 , 147 , 130 , 84.3, 52.2, 57.8 , 0   ]
    ]),

    '8' => new Matrix([
        // TP   LV    SV    VG    SL    CX     LB    BE   TC    PE    BS
        [0   , 85.5, 18.8, 29.6, 153 , 138  , 112 , 39.9],
        [85.5, 0   , 66.4, 107 , 189 , 173  , 147 , 87  ],
        [18.8, 66.4, 0   , 43.3, 167 , 151  , 130 , 21.7],
        [29.6, 107 , 43.3, 0   , 125 , 110  , 84.3, 64.5],
        [153 , 189 , 167 , 125 , 0   , 28.5 , 52.2, 188 ],
        [138 , 173 , 151 , 110 , 28.5, 0    , 57.8, 173 ],
        [112 , 147 , 130 , 84.3, 52.2, 57.8 , 0   , 147 ],
        [39.9, 87  , 21.7, 64.5, 188 , 173  , 147 , 0   ],
    ]),

    '9' => new Matrix([
        // TP   LV    SV    VG    SL    CX     LB    BE   TC    PE    BS
        [0   , 85.5, 18.8, 29.6, 153 , 138  , 112 , 39.9, 62.9],
        [85.5, 0   , 66.4, 107 , 189 , 173  , 147 , 87  , 88.2],
        [18.8, 66.4, 0   , 43.3, 167 , 151  , 130 , 21.7, 80.6],
        [29.6, 107 , 43.3, 0   , 125 , 110  , 84.3, 64.5, 33.1],
        [153 , 189 , 167 , 125 , 0   , 28.5 , 52.2, 188 , 91.1],
        [138 , 173 , 151 , 110 , 28.5, 0    , 57.8, 173 , 75.4],
        [112 , 147 , 130 , 84.3, 52.2, 57.8 , 0   , 147 , 49.5],
        [39.9, 87  , 21.7, 64.5, 188 , 173  , 147 , 0   , 97.2],
        [62.9, 88.2, 80.6, 33.1, 91.1, 75.4 , 49.5, 97.2, 0   ],
    ]),
*/
    /*'10' => new Matrix([
        // TP   LV    SV    VG    SL    CX     LB    BE   TC    PE    BS
        [0   , 85.5, 18.8, 29.6, 153 , 138  , 112 , 39.9, 62.9, 80.6],
        [85.5, 0   , 66.4, 107 , 189 , 173  , 147 , 87  , 88.2, 28.9],
        [18.8, 66.4, 0   , 43.3, 167 , 151  , 130 , 21.7, 80.6, 69.4],
        [29.6, 107 , 43.3, 0   , 125 , 110  , 84.3, 64.5, 33.1, 101 ],
        [153 , 189 , 167 , 125 , 0   , 28.5 , 52.2, 188 , 91.1, 162 ],
        [138 , 173 , 151 , 110 , 28.5, 0    , 57.8, 173 , 75.4, 146 ],
        [112 , 147 , 130 , 84.3, 52.2, 57.8 , 0   , 147 , 49.5, 141 ],
        [39.9, 87  , 21.7, 64.5, 188 , 173  , 147 , 0   , 97.2, 81.3],
        [62.9, 88.2, 80.6, 33.1, 91.1, 75.4 , 49.5, 97.2, 0   , 83.5],
        [80.6, 28.9, 69.4, 101 , 162 , 146  , 141 , 81.3, 83.5, 0   ],
    ]),*/
];

$output = '';
$descriptions = '';
$fitnessG = INF;
$best = null;

$teste = new PermutationChromosome([4, 8, 1, 10, 9, 2, 7, 0, 3, 6, 5]);
$func = new TSProblemFunction($cities);

foreach ($mutations as $mk => $mv) {
    $description["M"] = $mk;
    foreach ($crossovers as $ck => $cv) {
        $description["C"] = $ck;
        foreach ($selections as $sk => $sv) {
            $description["S"] = $sk;
            /*$pv = 100;
            $g = 100;
            $pcov = 0.8;
            $pmv = 0.3;*/
            foreach ($populationSize as $pv) {
                $description["POP"] = $pv;
                foreach ($generations as $g) {
                    $description["GEN"] = $g;
                    foreach ($probabilityCrossOver as $pcov) {
                        $description["PCO"] = $pcov;
                        foreach ($probabilityMutation as $pmv) {
                            $description["PM"] = $pmv;
                            foreach ($models as $mok => $mov) {
                                $description["MODEL"] = $mok;
                                foreach ($elitismo as $e) {
                                    $description["ELIT"] = $e;
                                    $time_start = microtime(true);
                                    for ($i = 0; $i < 100; ++$i) {
                                        $f = new TSProblemFunction($mov);
                                        $tsp = new GeneticTSP($mov->getM(), $pv, $g, $pmv, $pcov, $f, $mv, $sv, $cv, $e);
                                        $tsp->run();

                                        $text = "";
                                        $v = 0;
                                        foreach ($description as $dk => $dv) {
                                            if ($v) $text .= "_";
                                            $text .= "$dk:$dv";
                                            ++$v;
                                        }

                                        $descriptions .= "$text\n";

                                        $chr = new PermutationChromosome($tsp->getBest());
                                        $bestFitness = $f->compute($chr);

                                        echo "$text: $bestFitness {$chr }\n";
                                        file_put_contents("output_final_elitismo.txt", "$bestFitness\n", FILE_APPEND);
                                        file_put_contents("descriptions_final_elitismo.txt", "$text\n", FILE_APPEND);
                                    }
                                    $time_end = microtime(true);

                                    $time = $time_end - $time_start;
                                    echo "Resultado GA: $time segundos. {$mov->getM()}\n";

                                    $time_start = microtime(true);

                                    $teste = new PermutationChromosome();
                                    $teste->initialize($mov->getM());
                                    $func->compute($teste);
                                    $time_end = microtime(true);

                                    $time = ($time_end - $time_start) * fatorial($mov->getM());
                                    echo "Resultado BF: $time segundos. {$mov->getM()}\n";
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}


