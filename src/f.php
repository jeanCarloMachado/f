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


function foldTree($f, $g, $initial, $tree) {
    if ($tree == null || empty($tree)) {
        return $initial;
    }

    reset($tree);
    $headKey = key($tree);

    //string head has children
    if (is_string($headKey)) {
        return $f(
            //pass content o head
            foldTree($f,$g, $initial, \f\head($tree)),
            //pass head key
            $headKey
        );
    }

    //normal index so without subtrees
    return $g(
        //pass the rest of the ree
        foldTree($f,$g, $initial, \f\tail($tree)),
        //first value
        \f\head($tree)
    );
}

function mapTree($f, $tree) {
    $runAndAppend = function($a, $b) use ($f) {
        $a[] = $f($b);
        return $a;
    };
    $runAndIndice= function($a, $b) use ($f) {
        $newB = $f($b);
        return ["0$newB" => $a];
    };
    return foldTree($runAndIndice, $runAndAppend, [], $tree);
}
