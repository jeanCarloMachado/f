<?php

namespace f;

function appendList($l1, $l2) {
    return fold("f\op\append", $l1)($l2);
}

function allTrue($a)
{
    return fold('f\op\aANDb', null)($a);
}

function anyTrue($a)
{
    return fold('f\op\aORb', null)($a);
}


function noneTrue($a)
{
    return !anytrue($a);
}

function max($a) {
    return fold('f\op\greater', null)($a);
}

function min($a) {
    return fold('f\op\smaller', PHP_INT_MAX)($a);
}

function prune($x, $list) {
    $keepX = partial('f\op\keepN')($x);
    return fold($keepX, null)($list);
}

function partial(callable $callable, ...$args)
{
    $arity = (new \ReflectionFunction($callable))->getNumberOfRequiredParameters();

    return $args[$arity - 1] ?? false
        ? $callable(...$args)
        : function (...$passedArgs) use ($callable, $args) {
            return partial($callable, ...array_merge($args, $passedArgs));
        };
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
    return array_slice($xs, 1);
}

function infiniteSequence($init=0, $step=1) : \Generator {
    for ($i = $init ;; $i = $i + $step) {
        yield $i;
    }
}

function infinity() : \Generator {
    yield from infiniteSequence(0, 1);
};

function takeFrom(\Generator $range, int $num) {
    $result = [];
    foreach($range as $entry) {
        $result[] = $entry;
        if (count($result) == $num) {
            break;
        }
    }

    return $result;
}


// f -> a -> [a] -> a
function realfold(callable $f, $init, $list) {
    if (empty($list)) {
        return $init;
    }
    return $f(realfold($f, $init, allbutlast($list)), last($list));
};

function fold(callable $f, $init) {
    return \f\partial('f\realfold')($f, $init);
}

// f -> [] -> []
function map($f, $list)  {
    $applyAndAppend = function($f, $list, $b){
        return \f\op\append($list, $f($b));
    };
    $applyAndAppendPartial = partial($applyAndAppend)($f);
    return fold($applyAndAppendPartial, null)($list);
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

function last(array $a) {
    return array_values(
        array_slice($a, -1)
    )[0];
}

function lastKey(array $a) {
    $cpy = $a;
    end($cpy);
    $key = key($cpy);
    return $key;
}

function allbutlast(array $a) {
    return array_slice($a, 0, -1);
}

