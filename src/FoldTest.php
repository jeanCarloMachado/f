<?php
namespace f;

class FoldTest extends \PHPUnit\Framework\TestCase
{
    public function testSum() {
        $sumList = fold('f\op\sum', 0);
        $this->assertEquals(21, $sumList([6, 7, 8]));
    }

    public function testProduct() {

        $product = fold('f\op\product', 1);
        $this->assertEquals(6, $product([1,2,3]));
    }

    public function testAnyTrue() {
        $anytrue = fold('\f\op\aORb', false);
        $this->assertEquals(true, $anytrue([true, false, false]));
        $this->assertEquals(false, $anytrue([false, false, false]));
    }

    public function testAllTrue()
    {
        $allTrue = fold('f\op\aANDb', true);
        $this->assertTrue($allTrue([true, true, true]));
        $this->assertFalse($allTrue([true, false, true]));
    }

    public function testFoldAppend()
    {
        $this->assertEquals([1,2,3,4], \f\appendList([1,2], [3, 4]));
    }


    public function testSumMatrix()
    {
        $matrix = [
            [1,2,1],
            [1,2,1],
            [1,2,1],
        ];

        $sumList = fold('f\op\sum', 0);
        $applyFuncAndSum = function($a, $b) use ($sumList) {
            return call_user_func('f\op\sum', $a, $sumList($b));
        };
        $sumListOfLists = fold($applyFuncAndSum, 0);
        $this->assertEquals(12, $sumListOfLists($matrix));
    }

    public function testCount()
    {
        $addOne = function($a, $b) {
            return $a+1;
        };
        $count = fold($addOne, 0);
        $this->assertEquals(4, $count([3, 4, 5, 7]));
    }

    public function testDoubleAll()
    {
        $double  = partial('f\op\product')(2);

        $partialMap = partial('f\map');
        $this->assertEquals([2, 4, 8], $partialMap($double)([1, 2, 4]));
    }
}


