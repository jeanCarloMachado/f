<?php
require_once __DIR__.'/../vendor/autoload.php';

class Cons {
    private $value;
    private $type;
    public function __construct($value)
    {
        $this->value = $value;
        $this->type = gettype($value);
    }
    public function  __invoke($value) {
        return $value;
    }
    public function getType() {
        return $this->type;
    }
}

class Listof {
    private $elements = [];
    private $type;
    public static function fromScalar(array $array) {
        $list = new self;
        foreach($array as $entry) {
            $list->append(new Cons($entry));
        }

        return $list;
    }
    public function getType() {
        return $this->type;
    }
    public function append(Cons $c) {
        if (!$this->getType()) {
            $this->type  = $c->getType();
        }
        if ($c->getType() != $this->getType()) {
            throw new \Exception('elements have to be of same type');
        }
        $this->elements[] = $c;
    }
}
$list = Listof::fromScalar([1,2,1]);
