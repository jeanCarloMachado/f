<?php

function memoize($function) {
    return function () use ($function) {
        static $results = [];
        $args = func_get_args();
        $key = serialize($args);

        if (empty($results[$key]))  {
            $results[$key] = call_user_func_array($function, $args);
        }

        return $results[$key];
    };
}

$factorial = function($x) use (&$factorial) {
    if ($x == 1) {
        return 1;
    }
    return $x * $factorial($x - 1);
};




$memoizedFactorial = memoize($factorial);
$emoizedFactorial(99);
