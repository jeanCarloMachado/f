# f

f is a functional lib for php

**Note: this lib is not optimized for performance yet**


## Currying

```php
$threeArgs = function($a, $b, $c) {
    return [$a, $b, $c];
};
$treeArgsPartial  = \f\partial($threeArgs);
$firstAs666 = $treeArgsPartial(666);
$firstAs666(777, 888);
//[666,777,888]
```

## Fold

```php
$addOne = function($a, $b) {
    return $a+1;
};
$count = \f\fold($addOne, 0);
$count([3, 4, 5, 7]);
//4
```


## Pattern matching

```php
$sumList = \f\patternMatch([
    '[]' => 0,
    '(x:xs)' => function ($x, $xs) use (&$sumList) {
        return $x + $sumList($xs);
    }
]);

$sumList([1,2,3]);
//6
```

## Lazy loading

```php
\f\takeFrom(\f\infinity(), 5)
//[0,1,2,3,4]
```

## Memoize


```php
$calls = 0;
$factorial = function($x) use (&$factorial, &$calls) {
    $calls++;
    if ($x == 1) {
        return 1;
    }
    return $x * $factorial($x - 1);
};

$memoizedFactorial = \f\memoize($factorial);
$memoizedFactorial(4);
$memoizedFactorial(4);
//$calls = 4
```

## More

 - Tail
 - Head
 - Map

Contributions are welcome :)
