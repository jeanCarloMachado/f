<?php

namespace f;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class Node
{
    public $children = [];
    public $value;

    public function __construct($value, $children = []) {
        $this->value  = $value;
        $this->children  = $children;
    }
    public function hasChildren()
    {
        return !empty($this->children);
    }

    public static function turnIntoNode($value)
    {
        return new self($value);
    }

    public static function turnIntoNodeIfNotAlready($value)
    {
        return is_a($value, \f\Node::class) ? $value : new Node($value);
    }
}



