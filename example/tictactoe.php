<?php

require_once __DIR__.'/../vendor/autoload.php';

$board = [
    [null, null, null],
    [null, null, null],
    [null, null, null],
];

$possibleMoves = function($board) {
    $crossTime = (array_reduce($board, function($carry, $item) {
        return $carry + array_reduce($item, function($carry, $item) {
            if ($item == 'x')
                return $carry +  1;
            else return $carry;
        },0);
    }, 0) % 2) == 0;

    foreach($board as $rowIndice => $row) {
        foreach ($row as $itemIndice => $item) {
            if ($item != null) {
                continue;
            }
            $copy = $board;
            $copy[$rowIndice][$itemIndice] = ($crossTime) ? 'x' : 'o';

            yield  $copy;
        }
    }
};

foreach ($possibleMoves($board) as $move) {
    print_r($move);
}
