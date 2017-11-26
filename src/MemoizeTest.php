<?php

namespace f;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class MemoizeTest extends \PHPUnit\Framework\TestCase
{
    public function testMemoize()
    {
        $calls = 0;
        $factorial = function($x) use (&$factorial, &$calls) {
            $calls++;
            if ($x == 1) {
                return 1;
            }
            return $x * $factorial($x - 1);
        };

        $memoizedFactorial = memoize($factorial);
        $memoizedFactorial(4);
        $memoizedFactorial(4);
        $this->assertEquals(4, $calls);
    }
}


