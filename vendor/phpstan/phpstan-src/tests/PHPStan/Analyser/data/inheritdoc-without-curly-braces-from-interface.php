<?php

namespace _PhpScoperbd5d0c5f7638\InheritDocWithoutCurlyBracesFromInterface;

class Foo extends \_PhpScoperbd5d0c5f7638\InheritDocWithoutCurlyBracesFromInterface\FooParent implements \_PhpScoperbd5d0c5f7638\InheritDocWithoutCurlyBracesFromInterface\FooInterface
{
    /**
     * @inheritdoc
     */
    public function doFoo($string)
    {
        die;
    }
}
abstract class FooParent
{
}
interface FooInterface
{
    /**
     * @param string $string
     */
    public function doFoo($string);
}
