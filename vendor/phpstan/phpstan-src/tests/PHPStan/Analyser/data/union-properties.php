<?php

namespace _PhpScoperbd5d0c5f7638\UnionProperties;

class Foo
{
    /** @var self */
    private $doSomething;
}
class Bar
{
    /** @var self */
    private $doSomething;
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
    /** @var self */
    private static $doSomething;
}
class BarStatic
{
    /** @var self */
    private static $doSomething;
}
