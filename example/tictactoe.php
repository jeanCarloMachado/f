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

$endOfGame = function ($move) {

    for ($i=0; $i<3; $i++) {
        $horizontalWon = ($move[$i][0] != null && $move[$i][0] == $move[$i][1] && $move[$i][2] == $move[$i][0]);
        if ($horizontalWon) {
            return $move[$i][0];
        }
    }

    for($i=0; $i<3; $i++) {
        $verticalWon = ($move[0][$i] != null && $move[0][$i] == $move[1][$i] && $move[2][$i] == $move[0][$i]);
        if ($verticalWon) {
            return $move[0][$i];
        }
    }

    $diagonal1 = ($move[0][0] != null && $move[1][1] == $move[2][2] && $move[0][0] == $move[1][1]);
    if ($diagonal1) {
        return $move[1][1];
    }

    $diagonal2 = ($move[2][0] != null && $move[1][1] == $move[2][0] && $move[1][1] == $move[0][2]);
    if ($diagonal2) {
        return $move[1][1];
    }
    return false;

};

$moveValue = function($move) use ($endOfGame) : float {
    $end = $endOfGame($move);
    if (!$end) {
        return 0;
    }

    if ($end == 'o') {
        return -1;
    }

    //computer is 'x'
    return 1;
};

print_r($endOfGame($board));

$moveToNode = function($move) use ($possibleMoves, &$moveToNode) {
    $childrenBuilder = function($move) use ($moveToNode, $possibleMoves) {
        $result  = [];
        foreach($possibleMoves($move) as $subMove) {
            $result[]  = $moveToNode($subMove);
        }
        return $result;
    };
    $children = $childrenBuilder($move);
    return \f\Node::turnIntoNode($move, $children);
};
/* print_r($moveToNode($board)); */

