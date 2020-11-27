<?php

namespace _PhpScoperbd5d0c5f7638\ReturnTypes;

function returnNothing()
{
    return;
}
function returnInteger() : int
{
    if (\rand(0, 1)) {
        return 1;
    }
    if (\rand(0, 1)) {
        return 'foo';
    }
    $foo = function () {
        return 'bar';
    };
}
function returnObject() : \_PhpScoperbd5d0c5f7638\ReturnTypes\Bar
{
    if (\rand(0, 1)) {
        return 1;
    }
    if (\rand(0, 1)) {
        return new \_PhpScoperbd5d0c5f7638\ReturnTypes\Foo();
    }
    if (\rand(0, 1)) {
        return new \_PhpScoperbd5d0c5f7638\ReturnTypes\Bar();
    }
}
function returnChild() : \_PhpScoperbd5d0c5f7638\ReturnTypes\Foo
{
    if (\rand(0, 1)) {
        return new \_PhpScoperbd5d0c5f7638\ReturnTypes\Foo();
    }
    if (\rand(0, 1)) {
        return new \_PhpScoperbd5d0c5f7638\ReturnTypes\FooChild();
    }
    if (\rand(0, 1)) {
        return new \_PhpScoperbd5d0c5f7638\ReturnTypes\OtherInterfaceImpl();
    }
}
/**
 * @return string|null
 */
function returnNullable()
{
    if (\rand(0, 1)) {
        return 'foo';
    }
    if (\rand(0, 1)) {
        return null;
    }
}
function returnInterface() : \_PhpScoperbd5d0c5f7638\ReturnTypes\FooInterface
{
    return new \_PhpScoperbd5d0c5f7638\ReturnTypes\Foo();
}
/**
 * @return void
 */
function returnVoid()
{
    if (\rand(0, 1)) {
        return;
    }
    if (\rand(0, 1)) {
        return null;
    }
    if (\rand(0, 1)) {
        return 1;
    }
}
function returnAlias() : \_PhpScoperbd5d0c5f7638\ReturnTypes\Foo
{
    return new \_PhpScoperbd5d0c5f7638\ReturnTypes\FooAlias();
}
function returnAnotherAlias() : \_PhpScoperbd5d0c5f7638\ReturnTypes\FooAlias
{
    return new \_PhpScoperbd5d0c5f7638\ReturnTypes\Foo();
}
/**
 * @return int
 */
function containsYield()
{
    (yield 1);
    return;
}
/**
 * @return mixed[]|string|null
 */
function returnUnionIterable()
{
    if (something()) {
        return 'foo';
    }
    return [];
}
/**
 * @param array<int, int> $arr
 */
function arrayMapConservesNonEmptiness(array $arr) : int
{
    if (!$arr) {
        return 5;
    }
    $arr = \array_map(function ($a) : int {
        return $a;
    }, $arr);
    return \array_shift($arr);
}
/**
 * @return \Generator<int, string>
 */
function returnFromGeneratorMixed() : \Generator
{
    (yield 1);
    return 2;
}
/**
 * @return \Generator<int, int, int, string>
 */
function returnFromGeneratorString() : \Generator
{
    (yield 1);
    if (\rand(0, 1)) {
        return;
    }
    return 2;
}
/**
 * @return \Generator<int, int, int, void>
 */
function returnVoidFromGenerator() : \Generator
{
    (yield 1);
    return;
}
/**
 * @return \Generator<int, int, int, void>
 */
function returnVoidFromGenerator2() : \Generator
{
    (yield 1);
    return 2;
}
/**
 * @return never
 */
function returnNever()
{
    return;
}