<?php

use f\PartiallyApplyDeclare;


class PartiallyApplyTest extends \PHPUnit\Framework\TestCase
{
    public function testAll()
    {
        $sum = function($a, $b) {
            return $a+$b;
        };

        $sumPartial = new PartiallyApplyDeclare($sum);
        $sum5 = $sumPartial(5);
        $this->assertEquals(14, $sum5(9));

        $treeArgs = function($a, $b, $c) {
            return [$a, $b, $c];
        };
        $treeArgsPartial  = new PartiallyApplyDeclare($treeArgs);
        $firstAs666 = $treeArgsPartial(666);
        $this->assertEquals([666,777,888],$firstAs666(777, 888));
    }
}
