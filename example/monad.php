<?php
declare(strict_types=1);

/* abstract class Monad { */
/*     public $isValue = false; */

/*     protected function __construct($a) */
/*     { */
/*         $this->a = $a; */
/*     } */

/*     abstract function apply($m, $k); */
/* } */


class Term {

    private function __construct()
    {}

    static function cont(int $cont)
    {
        $instance = new self();
        $instance->cont = $cont;
        $instance->type = "cont";

        return $instance;
    }

    static function div(Term $term1, Term $term2)
    {
        $instance = new self();
        $instance->a = $term1;
        $instance->b = $term2;
        $instance->type = "div";

        return $instance;
    }
}


class ExceptionM
{
    public $val;
    private function __construct()
    {}

    static function raise(string $val)
    {
        $instance = new self();
        $instance->val = $val;
        $instance->type = "raise";
        return $instance;
    }

    static function ret(int $val)
    {
        $instance = new self();
        $instance->val = $val;
        $instance->type = "return";
        return $instance;
    }
}

function evaluate(Term $term) : ExceptionM {

    switch($term->type) {
        case "cont":
            return ExceptionM::ret($term->cont);
        case "div":
            $t = evaluate($term->a);
            switch($t->type) {
                case "raise":
                    return $t;
                case "return":
                    $u = evaluate($term->b);
                    switch ($u->type) {
                        case "raise":
                            return $u;
                        case "return":
                            if ($u->val == 0 ) {
                                ExceptionM::raise("divide by zero");
                            }

                            return ExceptionM::ret($t->val / $u->val);
                    }
            }
    }
}

function printMonad($m) {
    switch($m->type) {
        case "raise":
            echo  $m->val;
        case "return":
            echo $m->val;
    }
}

$term = Term::div(Term::cont(666), Term::div(Term::cont(333), Term::cont(111)));

printMonad(evaluate($term));



