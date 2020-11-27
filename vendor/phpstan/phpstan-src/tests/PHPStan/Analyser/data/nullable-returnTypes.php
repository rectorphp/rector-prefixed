<?php

namespace _PhpScoperbd5d0c5f7638\NullableReturnTypes;

class Foo
{
    public function doFoo() : ?int
    {
        die;
    }
    /**
     * @return int|null
     */
    public function doBar() : ?int
    {
    }
    /**
     * @return int
     */
    public function doConflictingNullable() : ?int
    {
    }
    /**
     * @return int|null
     */
    public function doAnotherConflictingNullable() : int
    {
    }
}