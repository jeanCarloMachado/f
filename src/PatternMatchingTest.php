<?php

namespace f;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class PatternMatchingTest extends \PHPUnit\Framework\TestCase
{
    public function testFactorial()
    {
        $factorial = patternMatch([
            '0' => 1,
            '_' => function ($n) use (&$factorial) {
                return $n * $factorial($n - 1);
            }
        ]);

        $this->assertEquals(120, $factorial(5));
    }

    public function testSumList()
    {
        $sumList = patternMatch([
            '[]' => 0,
            '(x:xs)' => function ($x, $xs) use (&$sumList) {
                return $x + $sumList($xs);
            }
        ]);

        $this->assertEquals(6, $sumList([1,2,3]));
    }
}


