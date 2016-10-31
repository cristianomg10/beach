<?php

namespace MathPHP\NumericalAnalysis\NumericalIntegration;

class NumbericalIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateAbstractClassException()
    {
        // Instantiating NumericalIntegration (an abstract class)
        $this->setExpectedException('\Error');
        new NumericalIntegration;
    }

    public function testIncorrectInput()
    {
        // The input $source is neither a callback or a set of arrays
        $this->setExpectedException('MathPHP\Exception\BadDataException');
        $x                 = 10;
        $incorrectFunction = $x**2 + 2 * $x + 1;
        NumericalIntegration::getPoints($incorrectFunction, [0,4,5]);
    }

    public function testNotCoordinatesException()
    {
        // An array doesn't have precisely two numbers (coordinates)
        $this->setExpectedException('MathPHP\Exception\BadDataException');
        NumericalIntegration::validate([[0,0], [1,2,3], [2,2]]);
    }

    public function testNotEnoughArraysException()
    {
        // There are not enough arrays in the input
        $this->setExpectedException('MathPHP\Exception\BadDataException');
        NumericalIntegration::validate([[0,0]]);
    }

    public function testNotAFunctionException()
    {
        // Two arrays share the same first number (x-component)
        $this->setExpectedException('MathPHP\Exception\BadDataException');
        NumericalIntegration::validate([[0,0], [0,5], [1,1]]);
    }
}
