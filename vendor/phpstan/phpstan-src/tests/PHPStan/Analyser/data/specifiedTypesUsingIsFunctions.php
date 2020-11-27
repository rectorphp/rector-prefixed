<?php

namespace _PhpScoperbd5d0c5f7638;

function () {
    if (!\is_int($integer)) {
        throw new \Exception();
    }
    if (!\is_integer($anotherInteger)) {
        throw new \Exception();
    }
    if (!\is_long($longInteger)) {
        throw new \Exception();
    }
    if (!\is_float($float)) {
        throw new \Exception();
    }
    if (!\is_double($doubleFloat)) {
        throw new \Exception();
    }
    if (!\is_real($realFloat)) {
        throw new \Exception();
    }
    if (!\is_null($null)) {
        throw new \Exception();
    }
    if (!\is_array($array)) {
        throw new \Exception();
    }
    if (!\is_bool($bool)) {
        throw new \Exception();
    }
    if (!\is_callable($callable)) {
        throw new \Exception();
    }
    if (!\is_resource($resource)) {
        throw new \Exception();
    }
    if (!\is_string($string)) {
        throw new \Exception();
    }
    if (!\is_object($object)) {
        throw new \Exception();
    }
    if (!\is_int($mixedInteger) && !\ctype_digit($whatever)) {
        return;
    }
    /** @var int|\stdClass $intOrStdClass */
    $intOrStdClass = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_numeric($intOrStdClass)) {
        return;
    }
    $foo = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_a($foo, '_PhpScoperbd5d0c5f7638\\Foo')) {
        return;
    }
    $anotherFoo = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_a($anotherFoo, \_PhpScoperbd5d0c5f7638\Foo::class)) {
        return;
    }
    \assert(\is_int($yetAnotherInteger));
    $subClassOfFoo = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo, \_PhpScoperbd5d0c5f7638\Foo::class)) {
        return;
    }
    function (\_PhpScoperbd5d0c5f7638\Foo $foo) {
        if (!\is_subclass_of($foo, '')) {
        }
    };
    /** @var string $someClass */
    $someClass = \_PhpScoperbd5d0c5f7638\doFoo();
    /** @var mixed $subClassOfFoo2 */
    $subClassOfFoo2 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo2, \_PhpScoperbd5d0c5f7638\Foo::class, \false)) {
        return;
    }
    /** @var mixed $subClassOfFoo3 */
    $subClassOfFoo3 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo3, $someClass)) {
        return;
    }
    /** @var mixed $subClassOfFoo4 */
    $subClassOfFoo4 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo4, $someClass, \false)) {
        return;
    }
    /** @var string|object $subClassOfFoo5 */
    $subClassOfFoo5 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo5, \_PhpScoperbd5d0c5f7638\Foo::class)) {
        return;
    }
    /** @var string|object $subClassOfFoo6 */
    $subClassOfFoo6 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo6, $someClass)) {
        return;
    }
    /** @var string|object $subClassOfFoo7 */
    $subClassOfFoo7 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo7, \_PhpScoperbd5d0c5f7638\Foo::class, \false)) {
        return;
    }
    /** @var string|object $subClassOfFoo8 */
    $subClassOfFoo8 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo8, $someClass, \false)) {
        return;
    }
    /** @var object $subClassOfFoo9 */
    $subClassOfFoo9 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo9, $someClass, \false)) {
        return;
    }
    /** @var object $subClassOfFoo10 */
    $subClassOfFoo10 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo10, $someClass)) {
        return;
    }
    /** @var object $subClassOfFoo11 */
    $subClassOfFoo11 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo11, \_PhpScoperbd5d0c5f7638\Foo::class)) {
        return;
    }
    /** @var object $subClassOfFoo12 */
    $subClassOfFoo12 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo12, \_PhpScoperbd5d0c5f7638\Foo::class, \false)) {
        return;
    }
    /** @var string|Foo|Bar|object $subClassOfFoo13 */
    $subClassOfFoo13 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo13, \_PhpScoperbd5d0c5f7638\Foo::class, \false)) {
        return;
    }
    /** @var string|Foo|Bar|object $subClassOfFoo14 */
    $subClassOfFoo14 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo14, $someClass, \false)) {
        return;
    }
    /** @var string|Foo|Bar|object $subClassOfFoo15 */
    $subClassOfFoo15 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo15, \_PhpScoperbd5d0c5f7638\Foo::class)) {
        return;
    }
    /** @var string|Foo|Bar $subClassOfFoo16 */
    $subClassOfFoo16 = \_PhpScoperbd5d0c5f7638\doFoo();
    if (!\is_subclass_of($subClassOfFoo16, $someClass)) {
        return;
    }
    die;
};
