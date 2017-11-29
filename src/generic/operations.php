<?php

namespace f\op;

function sum($a, $b) {
    return $a + $b;
}

function product($a, $b) {
    return $a * $b;
}

function aORb($a, $b) {
    return ($a or $b);
}

function aANDb($a, $b) {
    return ($a and $b);
}

function append($a, $b) {
    $a[] = $b;
    return $a;
};

function greater($a, $b) {
    if ($b > $a) {
        return $b;
    }
    return $a;
}

function smaller($a, $b) {
    if ($b < $a) {
        return $b;
    }
    return $a;
}

function keepN($n, $a, $b) {
    if (count($a) < $n) {
        $a[] = $b;
    }
    return $a;
}

function merge($a, $b) {
    return array_merge($a, $b);
}
