<?php

require_once __DIR__.'/../vendor/autoload.php';

function isPrime($x) {
    if ($x == 1)  {
        return 1;
    }
    if ($x == 2)  {
        return 0;
    }
    $seq = \f\takeFrom(\f\infiniteSequence(2), ($x - 2));
    $multipleOfX = \f\partial('f\op\aMultipleOfb')($x);
    $result  = \f\map($multipleOfX, $seq);
    return \f\noneTrue($result);
}

function primes() {
    foreach (\f\infiniteSequence(1) as $i) {
        if (isPrime($i)) {
            yield $i;
        }
    }
}

print_r(\f\takefrom(primes(), 10));

