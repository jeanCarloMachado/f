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
            '3',
            '01' => [
                '02' => [3, '01' => [1], 1],
                '2'
            ]
        ];

        $sum = function($a, $b) {
            return $a + $b;
        };

        $this->assertEquals(14, foldTree($sum, $sum, 0, $tree));
    }

    public function testAppendTree()
    {
        $tree = [
            2,
            '04' => [5, '07' => [1,3]]
        ];

        $f = function($a, $b) {
            if (is_array($b)) {
                $a = array_merge($a, $b);
            } else {
                $a[] = $b;
            }


            return $a;
        };

        $this->assertEquals([2,5,1,3,7,4], foldTree($f, $f, [], $tree));
    }

    public function testDoubleTree()
    {
        $tree = [
            '03' => [1,4],
            '01' => [1,'05' => [1,2, 3]]
        ];
        $double = function($a) {
            return $a*2;
        };
        $result  = mapTree($double, $tree);
        $expected = [
            '06' => [2,8],
            '02' => [2,'010' => [2,4,6]]
        ];

        $this->assertEquals($result, $expected);

    }

}


