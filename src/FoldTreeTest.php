<?php

namespace f;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class FoldTreeTest extends \PHPUnit\Framework\TestCase
{
    public function testFoldTreeSum()
    {
        $tree = [
            new Node(3),
            new Node(1, [
                new Node(2, [
                    new Node(3),
                    new Node(1, [
                        new Node(1)
                    ]),
                    new Node(1),
                ]),
                new Node(2),
            ])
        ];


        $this->assertEquals(14, \f\tree\fold('f\op\sum', 'f\op\sum', 0, $tree));
    }

    public function testFoldTreeProduct()
    {
        $tree = [
            new Node(2),
            new Node(2, [
                new Node(2, [
                    new Node(2),
                ]),
                new Node(2),
            ])
        ];

        $product = function($a, $b) {
            return $a * $b;
        };

        $this->assertEquals(32, \f\tree\fold($product, $product, 1, $tree));
    }


    public function testAppendTree()
    {
        $tree = [
            new Node(2),
            new Node(4, [
                new Node(5),
                new Node(7, [
                    new Node(1),
                    new Node(3),
                ]),
            ])
        ];

        $f = function($a, $b) {
            if (is_array($b)) {
                $a = array_merge($a, $b);
            } else {
                $a[] = $b;
            }


            return $a;
        };

        $this->assertEquals([2,5,1,3,7,4], \f\tree\fold($f, $f, [], $tree));
    }

    public function testDoubleTree()
    {
        $tree = [
            new Node(3, [
                new Node(1),
                new Node(4)
            ]),
            new Node(1, [
                new Node(1),
                new Node(5, [
                    new Node(1),
                    new Node(2),
                    new Node(3),
                ]),
            ])
        ];
        $double = \f\partial('f\op\product')(2);
        $result  = \f\tree\map($double, $tree);
        $expected = [
            new Node(6, [
                new Node(2),
                new Node(8)
            ]),
            new Node(2, [
                new Node(2),
                new Node(10, [
                    new Node(2),
                    new Node(4),
                    new Node(6),
                ]),
            ])
        ];
        $this->assertEquals($result[0]->value, $expected[0]->value);
    }

    public function testCapitalizeTree()
    {
        $tree = [
            new Node('a', [

                new Node('c')
            ]),
            new Node('e')
        ];
        $capitalize = function($a) {
            return strtoupper($a);
        };
        $result = \f\tree\map($capitalize, $tree);
        $expected = [
            new Node('A', [
                new Node('C')
            ]),
            new Node('E')
        ];
        $this->assertEquals($result, $expected);
    }

    public function testMaxTree()
    {
        $tree = [
            new Node(2),
            new Node(4, [
                new Node(5),
                new Node(1),
                new Node(9, [
                    new Node(3),
                ]),
            ])
        ];

        $this->assertEquals(9, \f\tree\fold('f\op\greater', 'f\op\greater', 0, $tree));
    }

    public function atestPruneTree()
    {
        $tree = [
            new Node(2),
            new Node(2),
            new Node(2),
        ];

        $keepX = partial('f\op\keepN')(2);
        $result = \f\tree\fold($keepX, $keepX, 0, $tree);


        $tree = [
            new Node(2),
            new Node(2),
        ];

        $this->assertEquals($result, $expect);
    }

}


