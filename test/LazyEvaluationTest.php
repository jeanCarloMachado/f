<?php

namespace f;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class LazyEvaluationTest extends \PHPUnit\Framework\TestCase
{

    public function testLazy()
    {
        $this->assertEquals([0,1,2,3,4], takeFrom(infinity(), 5));
    }
}


