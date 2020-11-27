<?php

namespace _PhpScoper88fe6e0ad041\AppendedArrayItem;

class Foo
{
    /** @var int[] */
    private $integers;
    /** @var callable[] */
    private $callables;
    public function doFoo()
    {
        $this->integers[] = 4;
        $this->integers['foo'] = 5;
        $this->integers[] = 'foo';
        $this->callables[] = [$this, 'doFoo'];
        $this->callables[] = [1, 2, 3];
        $this->callables[] = ['Closure', 'bind'];
        $this->callables[] = 'strpos';
        $this->callables[] = [__CLASS__, 'classMethod'];
        $world = 'world';
        $this->callables[] = ['Foo', "Hello {$world}"];
        $this->integers[] =& $world;
    }
    public function assignOp()
    {
        $this->integers[0] .= 'foo';
    }
}
class Bar
{
    /** @var (callable(): string)[] */
    private $stringCallables;
    public function doFoo()
    {
        $this->stringCallables[] = function () : int {
            return 1;
        };
    }
    public function doBar()
    {
        $this->stringCallables[] = function () : string {
            return 1;
        };
    }
}
class Baz
{
    /** @var array<static> */
    public $staticProperty;
}
class Lorem extends \_PhpScoper88fe6e0ad041\AppendedArrayItem\Baz
{
}
function (\_PhpScoper88fe6e0ad041\AppendedArrayItem\Lorem $lorem) {
    $lorem->staticProperty[] = new \_PhpScoper88fe6e0ad041\AppendedArrayItem\Lorem();
};
function (\_PhpScoper88fe6e0ad041\AppendedArrayItem\Lorem $lorem) {
    $lorem->staticProperty[] = new \_PhpScoper88fe6e0ad041\AppendedArrayItem\Baz();
};
