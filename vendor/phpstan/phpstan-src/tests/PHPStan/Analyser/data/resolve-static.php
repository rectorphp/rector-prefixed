<?php

namespace _PhpScoper88fe6e0ad041\ResolveStatic;

class Foo
{
    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }
    /**
     * @return array{foo: static}
     */
    public function returnConstantArray() : array
    {
        return [$this];
    }
    /**
     * @return static
     */
    public function nullabilityNotInSync() : ?self
    {
    }
    /**
     * @return static|null
     */
    public function anotherNullabilityNotInSync() : self
    {
    }
}
class Bar extends \_PhpScoper88fe6e0ad041\ResolveStatic\Foo
{
}
function (\_PhpScoper88fe6e0ad041\ResolveStatic\Bar $bar) {
    die;
};
