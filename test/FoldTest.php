<?php
namespace f;

class MaybeTest extends \PHPUnit\Framework\TestCase
{
	public function testBasicFunctionality()
	{
		$maybe = Maybe::just('gandalf');
		$this->assertFalse($maybe->isNothing());
		$this->assertTrue($maybe->isJust());

		$maybe->match(
			function($value) {
				$this->assertEquals('gandalf', $value);
			},
			function() {
				$this->assertTrue(false);
			}
		);

    }
}


