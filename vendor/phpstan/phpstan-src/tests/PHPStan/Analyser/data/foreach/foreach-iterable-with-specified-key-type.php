<?php

namespace _PhpScoper88fe6e0ad041\ForeachWithGenericsPhpDoc;

class Foo
{
    /**
     * @param iterable<self|Bar, string|int|float> $list
     */
    public function doFoo(iterable $list)
    {
        foreach ($list as $key => $value) {
            die;
        }
    }
}
