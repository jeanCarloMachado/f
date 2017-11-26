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
            if ($x == $key) {
                return $callOrReturn($whatToDo, $x);
            } elseif ($key === '_')  {
                return $callOrReturn($whatToDo, $x);
            }

        }
        throw new \Exception('No match found');
    };
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


