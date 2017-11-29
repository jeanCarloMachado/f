<?php

namespace f\tree;


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


function map($f, $tree) {
    $applyMakeNodeAndJoinChildren = function ($append, $f, $a, $b)  {
        if (is_array($b)) {
            $last = \f\Node::turnIntoNodeIfNotAlready(\f\last($b));
            $last->children = array_map(
                    ["f\Node", "turnIntoNodeIfNotAlready"],
                    \f\allbutlast($b)
                );
            return $append($a, $last);
        }

        $value = $f($b);
        $bResult = \f\Node::turnIntoNodeIfNotAlready($value);
        return $append($a, $bResult);
    };
    $partialApplyMakeNodeAndJoinChildren = \f\partial($applyMakeNodeAndJoinChildren)('f\op\append', $f);
    return fold($partialApplyMakeNodeAndJoinChildren, 'f\op\merge', [], $tree);
}
