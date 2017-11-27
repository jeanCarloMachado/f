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
            '01' => [
                '03' => [
                    '4',
                ],
                '02',
            ]
        ];


        $sum = function($a, $b) {
            return $a + $b;
        };

        $this->assertEquals(10, foldTree($sum, $sum, 0, $tree));
        $tree = [
            '01' => [
                '02',
                '03' => [
                    '4',
                ],
            ]
        ];
        $this->assertEquals(10, foldTree($sum, $sum, 0, $tree));
    }

    public function testAppend()
    {
        $tree = [
            '01' => [
                '02',
                '03' => [
                    '4',
                ]
            ]
        ];
        $append = function($a, $b) {
            $a[] = (int) $b;
            return $a;
        };
        $merge = function($a, $b) {
            return array_merge($a, $b);
        };
        $this->assertEquals([4,3,2,1], foldTree($append, $merge, [], $tree));
    }

    public function testDoubleTree()
    {
        $tree = [
            '01' => [
                '03' => [
                    '4',
                ],
                '02',
            ]
        ];
        $double = function($a) {
            return $a*2;
        };

        $doubledTree = mapTree($double, $tree);

        $sum = function($a, $b) {
            return $a + $b;
        };

        $this->assertEquals(20, foldTree($sum, $sum, 0, $doubledTree));
    }

}


