<?php

namespace f\tree;

function map($f, $tree) {
    $runAndJoinChildren = function($a, $b) use ($f) {
        if (is_array($b)) {
            $last = \f\Node::turnIntoNodeIfNotAlready(\f\last($b));
            $last->children = array_map(
                    ["f\Node", "turnIntoNodeIfNotAlready"],
                    \f\allbutlast($b)
                );
            $a[] = $last;
        } else {

            $value = $f($b);
            $result = \f\Node::turnIntoNodeIfNotAlready($value);
            $a[] = $result;
        }
        return $a;
    };
    return fold($runAndJoinChildren, 'f\op\merge', [], $tree);
}

function fold($f, $g, $initial, $tree) {
    if ($tree == null || empty($tree)) {
        return $initial;
    }
    if (is_object($tree)) {
        return $f($initial, $tree->value);
    }

    $last = \f\last($tree);

    if ($last->hasChildren()) {
        $last = $f(fold($f,$g,$initial, $last->children), $last->value);
        return $f(
            fold($f,$g,$initial, \f\allbutlast($tree)),
            $last
        );
    }

    return $g(
        fold($f, $g, $initial, \f\allbutlast($tree)),
        fold($f, $g, $initial, $last)
    );

}

