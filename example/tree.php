<?php
require_once __DIR__.'/../vendor/autoload.php';

$tree = [
    '01' => [
        '02',
        '03' => [
            '4',
        ]
    ]
];

$foldTree = function($f, $initial, $tree) use (&$foldTree) {
    if ($tree == null || empty($tree)) {
        return $initial;
    }

    reset($tree);
    $headKey = key($tree);

    //string head has children
    if (is_string($headKey)) {
        return $f(
            //pass head key
            $headKey,
            //pass content o head
            $foldTree($f, $initial, \f\head($tree))
        );
    }

    //normal index so without subtrees
    return $f(
        //first value
        \f\head($tree),
        //pass the rest of the ree
        $foldTree($f, $initial, \f\tail($tree))
    );
};

$sum = function($a, $b) {
    return $a + $b;
};

echo "Sum:".$foldTree($sum, 0, $tree);
echo PHP_EOL;

$append = function($a, $b) {
    $b[] = (int) $a;
    return $b;
};

echo "Append:";
echo PHP_EOL;
print_r($foldTree($append, [], $tree));

$mapTree = function ($f, $tree) use ($foldTree) {
    $runAndAppend = function($a, $b) use ($f) {
        $b[] = $f($a);
        return $b;
    };
    return $foldTree($runAndAppend, [], $tree);
};

$double = function($a) {
    return $a*2;
};
echo "Map:";
echo PHP_EOL;
print_r($mapTree($double, $tree));


