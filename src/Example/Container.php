<?php

namespace f;

class  Container {
    private $factories = [];

    public function __set($key, $func) {
        $this->factories[$key] = $func;
    }

    public function __get($key) {
        return $this->factories[$key]($this);
    }
}
$container = new Container();
$container->sumOne = function($number) {
    return $number + 1;
};


echo $container->sumOne;



