<?php

namespace _PhpScoperbd5d0c5f7638\ArrayObjectType;

use _PhpScoperbd5d0c5f7638\AnotherNamespace\Foo;
class Test
{
    const ARRAY_CONSTANT = [0, 1, 2, 3];
    const MIXED_CONSTANT = [0, 'foo'];
    public function doFoo()
    {
        /** @var Foo[] $foos */
        $foos = foos();
        foreach ($foos as $foo) {
            die;
        }
    }
}
