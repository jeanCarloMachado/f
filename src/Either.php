<?php

declare(strict_types=1);

namespace f;

final class Either
{
    private $isLeft;
    private $value;

    private function __construct(bool $isLeft, $value = null)
    {
        $this->isLeft = $isLeft;
        $this->value = $value;
    }

    public static function left($a) : self
    {
        return new self(true, $a);
    }

    public static function right($a) : self
    {
        return new self(false, $a);
    }

    public function isLeft() : bool
    {
        return $this->isLeft;
    }

    public function isNothing() : bool
    {
        return !$this->isLeft;
    }
}
