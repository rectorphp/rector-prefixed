<?php

namespace _PhpScoperbd5d0c5f7638\IteratorToArray;

use Traversable;
use function iterator_to_array;
use function PHPStan\Analyser\assertType;
class Foo
{
    /**
     * @param Traversable<string, int> $ints
     */
    public function doFoo(\Traversable $ints)
    {
        \PHPStan\Analyser\assertType('array<string, int>', \iterator_to_array($ints));
    }
}