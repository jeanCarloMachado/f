<?php

namespace f;

function partial($f) {
    return new PartiallyApplyDeclare($f);
}

function patternMatch (array $config) {
    $callOrReturn = function($candidate, $param) {
        if (is_callable($candidate))
            return call_user_func($candidate, $param);
        else
            return $candidate;
    };

    return function($x) use ($config, $callOrReturn) {
        foreach($config as $key => $whatToDo)  {
            if ($x == $key || $key === '_' || ($key === '[]' && $x === [])) {
                return $callOrReturn($whatToDo, $x);
            } elseif ($key === '(x:xs)') {
                return call_user_func($whatToDo, head($x), tail($x));
            }

        }
        throw new \Exception('No match found');
    };
}
// [a] -> a
function head($xs) {
    $result = reset($xs);
    return $result;
}

// [a] -> [a]
function tail($xs) : array {

    if (!$xs) {
        return [];
    }

    $result = [];
    $counter = 1;
    foreach($xs as $key => $entry) {
        if ($counter != 1) {
            $result[$key] = $entry;
        }
        $counter++;
    }

    return $result;
}


function infinity() : \Generator {
    for ($i =0;;$i++) {
        yield $i;
    }
};


function takeFrom(\Generator $range, int $num) {
    $result = [];
    foreach($range as $entry) {
        $result[] = $entry;
        if (count($result) >= $num) {
            break;
        }
    }

    return $result;
}


// f -> a -> [a] -> a
function fold($callable, $init) {
    $fold = function($list) use ($init, $callable, &$fold) {
        if (empty($list)) {
            return $init;
        }

        $last = array_pop($list);
        return $callable($fold($list), $last);
    };
    return $fold;
};

// f -> [] -> []
function map ($func, $list)  {
    $appendOne = function($a, $b) {
        $a[] = $b;
        return $a;
    };
    $applyAndAppend = function($func, $list, $b) use ($appendOne) {
        return $appendOne($list, $func($b));
    };
    $applyAndAppendPartial = partial($applyAndAppend);
    return fold($applyAndAppendPartial($func), null)($list);
}

// f -> f -> a
function memoize($function) {
    static $results = [];
    return function () use ($function, &$results) {
        $args = func_get_args();
        $key = serialize($args);

        if (empty($results[$key]))  {
            $results[$key] = call_user_func_array($function, $args);
            return $results[$key];
        }

        return $results[$key];
    };
}


function foldTree($receiveScalar, $receiveArray, $initial, $tree) {
    if ($tree == null || empty($tree)) {
        return $initial;
    }

    $headKey = key($tree);

    if (is_string($headKey))  {
        $headArray =  $receiveScalar(foldTree($receiveScalar,$receiveArray, $initial, \f\head($tree)), $headKey);
        return $receiveArray(
            foldTree($receiveScalar,$receiveArray, $initial, \f\tail($tree)),
            $headArray,
            $headKey
        );
    }

    return $receiveScalar(
        foldTree($receiveScalar,$receiveArray, $initial, \f\tail($tree)),
        \f\head($tree),
        $headKey
    );
}

function mapTree($f, $tree) {
    $runAndAppend = function($a, $b, $key = -1) use ($f) {
        if ($key == -1) {
            return $a;
        }
        $a[$key] = $f($b);
        return $a;
    };
    $mergeTree= function($a, $b, $key) use ($f) {
        $a["0".$f($key)] = $b;
        return $a;
    };
    return foldTree($runAndAppend, $mergeTree, [], $tree);
}
