<?php

namespace _PhpScoperbd5d0c5f7638\UnionMethods;

class Foo
{
    public function doSomething() : self
    {
    }
}
class Bar
{
    public function doSomething() : self
    {
    }
}
class Baz
{
    /**
     * @param Foo|Bar $something
     */
    public function doFoo($something)
    {
        die;
    }
}
class FooStatic
{
    public static function doSomething() : self
    {
    }
}
class BarStatic
{
    public static function doSomething() : self
    {
    }
}
