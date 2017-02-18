<?php
/**
 * Created by PhpStorm.
 * User: cristiano
 * Date: 2/18/17
 * Time: 11:22 AM
 */

namespace App\Utils\Functions\ObjectiveFunctions;


namespace App\Utils\Functions\ObjectiveFunctions;

use App\Classifiers\ANN\SingleLayerPerceptron;

class SLPFunction implements IObjectiveFunction
{

    private $slp;
    private $input;

    public function compute($individual)
    {
        if (is_array($individual)){
            $this->slp->setWeights()    ;
        } else {
            $this->slp->setWeights($individual->getGenes());
        }

        return $this->slp->run($this->input);
    }

    public function setInput($input){
        $this->input = $input;
    }

    public function __construct(SingleLayerPerceptron $slp)
    {
        $this->slp = $slp;
    }
}