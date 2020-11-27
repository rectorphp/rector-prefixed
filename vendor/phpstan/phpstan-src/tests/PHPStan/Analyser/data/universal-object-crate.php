<?php

namespace _PhpScoperbd5d0c5f7638\UniversalObjectCrate;

class Foo extends \stdClass
{
    /** @var string */
    private $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function doFoo() : void
    {
        $this->doBar($this->name);
        $this->doBaz($this->name);
        // reported - string passed to int
    }
    public function doBar(string $name) : void
    {
    }
    public function doBaz(int $i) : void
    {
    }
}
function () {
    $foo = new \_PhpScoperbd5d0c5f7638\UniversalObjectCrate\Foo('foo');
    $foo->doBaz($foo->name);
    // not reported, is mixed here
};
