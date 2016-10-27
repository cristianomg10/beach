<?php
namespace MathPHP\LinearAlgebra;

class MatrixColumnOperationsTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->A = [
            [1, 2, 3],
            [2, 3, 4],
            [4, 5, 6],
        ];
        $this->matrix = MatrixFactory::create($this->A);
    }

    /**
     * @dataProvider dataProviderForColulmnInterchange
     */
    public function testColulmnInterchange(array $A, int $nᵢ, int $nⱼ, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->columnInterchange($nᵢ, $nⱼ));
    }

    public function dataProviderForColulmnInterchange()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 1,
                [
                    [2, 1, 3],
                    [3, 2, 4],
                    [4, 3, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 2,
                [
                    [1, 3, 2],
                    [2, 4, 3],
                    [3, 5, 4],
                ]
            ],
            [
                [
                    [5, 5],
                    [4, 4],
                    [2, 7],
                    [9, 0],
                ], 0, 1,
                [
                    [5, 5],
                    [4, 4],
                    [7, 2],
                    [0, 9],
                ]
            ],
        ];
    }

    public function testColumnInterchangeExceptionColumnGreaterThanN()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('MathPHP\Exception\MatrixException');
        $A->columnInterchange(4, 5);
    }

    /**
     * @dataProvider dataProviderForColumnMultiply
     */
    public function testColumnMultiply(array $A, int $nᵢ, $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->columnMultiply($nᵢ, $k));
    }

    public function dataProviderForColumnMultiply()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 5,
                [
                    [5, 2, 3],
                    [10, 3, 4],
                    [15, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 4,
                [
                    [1, 8, 3],
                    [2, 12, 4],
                    [3, 16, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 2, 8,
                [
                    [1, 2, 24],
                    [2, 3, 32],
                    [3, 4, 40],
                ]
            ],
        ];
    }

    public function testColumnMultiplyExceptionColumnGreaterThanN()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('MathPHP\Exception\MatrixException');
        $A->columnMultiply(4, 5);
    }

    public function testColumnMultiplyExceptionKIsZero()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('MathPHP\Exception\BadParameterException');
        $A->columnMultiply(2, 0);
    }

    /**
     * @dataProvider dataProviderForColumnAdd
     */
    public function testColumnAdd(array $A, int $nᵢ, $nⱼ, int $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->columnAdd($nᵢ, $nⱼ, $k));
    }

    public function dataProviderForColumnAdd()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 1, 2,
                [
                    [1, 4, 3],
                    [2, 7, 4],
                    [3, 10, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 2, 3,
                [
                    [1, 2, 9],
                    [2, 3, 13],
                    [3, 4, 17],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 2, 4,
                [
                    [1, 2, 7],
                    [2, 3, 12],
                    [3, 4, 17],
                ]
            ],
        ];
    }


    public function testColumnAddExceptionRowGreaterThanN()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('MathPHP\Exception\MatrixException');
        $A->columnAdd(4, 5, 2);
    }

    public function testColumnAddExceptionKIsZero()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('MathPHP\Exception\BadParameterException');
        $A->columnAdd(1, 2, 0);
    }

    /**
     * @dataProvider dataProviderForColumnExclude
     */
    public function testColumnExclude(array $A, int $nᵢ, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->columnExclude($nᵢ));
    }

    public function dataProviderForColumnExclude()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0,
                [
                    [2, 3],
                    [3, 4],
                    [4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1,
                [
                    [1, 3],
                    [2, 4],
                    [3, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 2,
                [
                    [1, 2],
                    [2, 3],
                    [3, 4],
                ]
            ],
        ];
    }

    public function testColumnExcludeExceptionColumnDoesNotExist()
    {
        $this->setExpectedException('MathPHP\Exception\MatrixException');
        $this->matrix->columnExclude(-5);
    }
}
