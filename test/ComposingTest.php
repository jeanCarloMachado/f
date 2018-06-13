<?php

namespace f;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class EmbelishTest extends \PHPUnit\Framework\TestCase
{
    public function testEmbelish()
    {
		$isEven = function (int $int) {
			return $int %2 == 0;
		};
		$negate = function (bool $bool) {
			return !$bool;
		};

		//composition

		$tmp = $isEven(9);
		$negate($tmp);


		$logPair = function(callable $fun, $logMessage) {
			return function($x) use ($fun, $logMessage) {
				return [$fun($x), $logMessage];
			};
		};


		$isEvenLog = $logPair($isEven, "isEven");
		$negateLog = $logPair($negate, "negate");

		//composition don't work
		/* $tmp = $isEvenLog(9); */
		/* $negateLog($tmp); */

		$composePair = function(callable $a, callable $b) {
			return function($x) use ($a, $b) {
				$aPair = $a($x);
				$aFirst = reset($aPair);
				$aSecond = end($aPair);
				$bPair = $b($aFirst);
				$bFirst = reset($bPair);
				$bSecond= end($bPair);
				return [$bFirst, ($aSecond." ".$bSecond)];
			};

		};

		$composed = $composePair($isEvenLog, $negateLog);
		$composed(9);

    }
}


