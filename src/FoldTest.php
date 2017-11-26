<?php
namespace f;
use \f\PartiallyApplyDeclare;
require __DIR__.'/fold.php';

class FoldTest extends \PHPUnit\Framework\TestCase
{
    public function testSum() {
        $sum = function($a, $b) {
            return $a+$b;
        };
        $sumList = fold($sum, 0);
        $this->assertEquals(21, $sumList([6, 7, 8]));
    }

    public function testProduct() {

        $multiplyTwo = function($a, $b) {
            return $a*$b;
        };
        $product = fold($multiplyTwo, 1);
        $this->assertEquals(6, $product([1,2,3]));
    }

    public function testAnyTrue() {
        $or = function($a, $b) {
            return ($a or $b);
        };
        $anytrue = fold($or, false);
        $this->assertEquals(true, $anytrue([true, false, false]));
        $this->assertEquals(false, $anytrue([false, false, false]));
    }

    public function testAllTrue() {

        $and = function($a, $b) {
            return ($a and $b);
        };
        $allTrue = fold($and, true);
        $this->assertTrue($allTrue([true, true, true]));
        $this->assertFalse($allTrue([true, false, true]));
    }

    public function testAppend() {

        $appendOne = function($a, $b) {
            $a[] = $b;
            return $a;
        };
        $append = fold($appendOne, [1, 2]);
        $this->assertEquals([1,2,3,4], $append([3, 4]));
    }


    public function testSumMatrix()
    {
        $matrix = [
            [1,2,1],
            [1,2,1],
            [1,2,1],
        ];

        $sum = function($a, $b) {
            return $a+$b;
        };
        $sumList = fold($sum, 0);
        $applyFuncAndSum = function($a, $b) use ($sum, $sumList) {
            return $sum ($a, $sumList($b));
        };
        $sumListOfLists = fold($applyFuncAndSum, 0);
        $this->assertEquals(12, $sumListOfLists($matrix));
    }

    public function testCount()
    {
        $sum = function($a, $b) {
            return $a+$b;
        };
        $sumPartial = new PartiallyApplyDeclare($sum);
        $sum1 = $sumPartial(1);
        $count = fold($sum1, 0);
        $this->assertEquals(4, $count([3, 4, 5, 7]));
    }

    public function testDoubleAll()
    {
        $product = function($a, $b) {
            return $a * $b;
        };
        $productPartial = new PartiallyApplyDeclare($product);
        $double  = $productPartial(2);

        $partialMap = new PartiallyApplyDeclare('map');
        $this->assertEquals([2, 4, 8], $partialMap($double)([1, 2, 4]));
    }

}


