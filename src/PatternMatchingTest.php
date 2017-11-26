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
}


