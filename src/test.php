<?php

use function Functional\true;
use function Functional\false;
use function Functional\map;
use function Functional\select;
use function Functional\reject;
use function Functional\sort;

class User {
    public function __construct($active=false)
    {
        $this->active = $active;
    }
    public function isActive()
    {
        return $this->active;
    }
}
$collection = ['b','c','a'];
$result = sort($collection, function($left, $right) {
    return strcmp($left, $right);
});

$users = [
    new User(true),
    new User(false),
    new User(true),
    new User(true)
];
$fn = function($user, $collectionKey, $collection) {
    return $user->isActive();
};
$selected = reject($users, $fn);

$subtractor = function (ra, $b) {
    return $a - $b;
};
$result = $subtractor(10, 20); // -> -10

$subtractor = function($a) {
    return function($b) use ($a) {
        return $a - $b;
    };
};
print_r($subtractor(10)(7));

