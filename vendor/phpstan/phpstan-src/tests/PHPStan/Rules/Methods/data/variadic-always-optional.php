<?php

namespace _PhpScoperbd5d0c5f7638\VariadicParameterAlwaysOptional;

class Foo
{
    public function doFoo(string ...$test) : void
    {
    }
    public function doBar() : void
    {
    }
}
class Bar extends \_PhpScoperbd5d0c5f7638\VariadicParameterAlwaysOptional\Foo
{
    public function doFoo(string ...$test) : void
    {
    }
    public function doBar(...$test) : void
    {
    }
}