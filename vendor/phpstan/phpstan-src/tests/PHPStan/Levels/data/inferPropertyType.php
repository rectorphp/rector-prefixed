<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\InferPropertyType;

class Foo
{
    private $foo;
    private $bar;
    public function __construct(\DateTime $foo)
    {
        $this->foo = $foo;
        $this->bar = $this->bar;
    }
    public function doFoo()
    {
        $this->foo->formatt();
    }
}