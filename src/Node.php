<?php

namespace f;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class Node
{
    public $children;
    public $value;

    public function __construct($value, $children = [])
    {
        $this->value = $value;
        $this->children = $children;
    }

    public function isLeaf()
    {
        return empty($this->children);
    }

    public function isFork()
    {
        return !$this->isLeaf();
    }

    public function addChild(Node $node)
    {
        $this->children[] = $node;
    }

}

