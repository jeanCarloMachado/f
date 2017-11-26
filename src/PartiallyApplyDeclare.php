<?php

namespace f;

class PartiallyApplyDeclare {

    private $args;
    private $requiredArgs;
    private $callable;

    public function __construct($callable, $args = [])
    {
        $fct = new \ReflectionFunction($callable);
        $this->requiredArgs =  $fct->getNumberOfRequiredParameters();
        $this->callable = $callable;
        $this->args = $args;
    }
    public function __invoke(...$passedArgs)
    {
        $argsCpy = $this->args;
        $passedArgs = func_get_args();
        foreach ($passedArgs as $arg) {
            $argsCpy[] = $arg;
        }
        if (count($argsCpy) >= $this->requiredArgs ) {
            return call_user_func_array($this->callable, $argsCpy);
        }

        return new self($this->callable, $argsCpy);
    }
}

