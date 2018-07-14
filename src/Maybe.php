<?php

declare(strict_types=1);

namespace f;

final class Maybe
{
    private $isJust;
    private $value;

    private function __construct(bool $isJust, $value = null)
    {
        $this->isJust = $isJust;
        $this->value = $value;
    }

    public static function just($a) : self
    {
        return new self(true, $a);
    }

    public static function nothing() : self
    {
        return new self(false);
    }

    public function isJust() : bool
    {
        return $this->isJust;
    }

    public function isNothing() : bool
    {
        return !$this->isJust;
    }

    public function getValueProvidingFallback($defaultValue)
    {
        //default and value should be of the same type
        if  ($this->isJust()) {
            return $this->value;
        }

        return $defaultValue;
    }
}
