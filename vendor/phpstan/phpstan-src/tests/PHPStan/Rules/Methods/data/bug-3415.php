<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Bug3415;

trait FooParentTrait
{
    public function bar() : void
    {
        echo "bar";
    }
}
trait Foo
{
    use FooParentTrait;
}
class SomeClass
{
    use Foo {
        bar as baz;
    }
    public function __construct()
    {
        $this->bar();
        $this->baz();
    }
}