<?php

namespace _PhpScoperbd5d0c5f7638\DeclarationWarning;

@\mkdir('/foo/bar');
require __DIR__ . '/trigger-warning.php';
class Foo
{
    public function doFoo() : void
    {
    }
}
class Bar extends \_PhpScoperbd5d0c5f7638\DeclarationWarning\Foo
{
    public function doFoo(int $i) : void
    {
    }
}
