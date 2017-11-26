<?php

namespace f;

require __DIR__ . '/../vendor/autoload.php';

function newtonImperative($n, $eps = 0.00001) {
    $x = $n+1 / $n; //close approximation
    for(;;) {
       $xPlusOne =  ($x + ($n / $x)) / 2;
       if(abs($x - $xPlusOne ) < $eps) {
           break;
       }
       $x = $xPlusOne;
    }

    return $xPlusOne;
}


$next = function ($n, $x) {
    return ($x + $n/$x)/2;
};
$partialNext = new PartiallyApplyDeclare($next);

$repeat = function($f, $a)  use (&$repeat) {
    $result =  $f($a);
    yield $result;
    yield from $repeat($f, $result);
};

$approximation =  function($n) {
    return ($n + 1 / $n);
};

$newtonDeclarative = function($n, $eps = 0.00001) use ($approximation, $repeat, $partialNext)  {
    $a0 = $approximation($n);
    $nextWithFixedN = $partialNext($n);
    $x = null;
    foreach ($repeat($nextWithFixedN, $a0) as $xPlusOne) {
        if (!$x) {
            $x = $xPlusOne;
            continue;
        }
        if (abs($x - $xPlusOne) <  $eps) {
            break;
        }
        $x = $xPlusOne;
    }
    return $xPlusOne;
};

echo $newtonDeclarative(9);
echo newtonImperative(9);


