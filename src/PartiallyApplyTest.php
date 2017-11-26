<?php

namespace f;

class PartiallyApplyTest extends \PHPUnit\Framework\TestCase
{
    public function testSum()
    {
        $sum = function($a, $b) {
            return $a+$b;
        };

        $sumPartial = partial($sum);
        $sum5 = $sumPartial(5);
        $this->assertEquals(14, $sum5(9));
    }

    public function testThreeArgs()
    {
        $threeArgs = function($a, $b, $c) {
            return [$a, $b, $c];
        };
        $treeArgsPartial  = new PartiallyApplyDeclare($threeArgs);
        $firstAs666 = $treeArgsPartial(666);
        $this->assertEquals([666,777,888],$firstAs666(777, 888));
    }
}
