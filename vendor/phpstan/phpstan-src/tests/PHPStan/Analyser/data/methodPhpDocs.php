<?php

namespace _PhpScoperbd5d0c5f7638\MethodPhpDocsNamespace;

use _PhpScoperbd5d0c5f7638\SomeNamespace\Amet as Dolor;
use _PhpScoperbd5d0c5f7638\SomeNamespace\Consecteur;
class Foo extends \_PhpScoperbd5d0c5f7638\MethodPhpDocsNamespace\FooParent
{
    /**
     * @return Bar
     */
    public static function doSomethingStatic()
    {
    }
    /**
     * @param Foo|Bar $unionTypeParameter
     * @param int $anotherMixedParameter
     * @param int $anotherMixedParameter
     * @paran int $yetAnotherMixedProperty
     * @param int $integerParameter
     * @param integer $anotherIntegerParameter
     * @param aRray $arrayParameterOne
     * @param mixed[] $arrayParameterOther
     * @param Lorem $objectRelative
     * @param \SomeOtherNamespace\Ipsum $objectFullyQualified
     * @param Dolor $objectUsed
     * @param null|int $nullableInteger
     * @param Dolor|null $nullableObject
     * @param Dolor $anotherNullableObject
     * @param self $selfType
     * @param static $staticType
     * @param Null $nullType
     * @param Bar $barObject
     * @param Foo $conflictedObject
     * @param Baz $moreSpecifiedObject
     * @param resource $resource
     * @param array[array] $yetAnotherAnotherMixedParameter
     * @param \\Test\Bar $yetAnotherAnotherAnotherMixedParameter
     * @param New $yetAnotherAnotherAnotherAnotherMixedParameter
     * @param void $voidParameter
     * @param Consecteur $useWithoutAlias
     * @param true $true
     * @param false $false
     * @param true $boolTrue
     * @param false $boolFalse
     * @param bool $trueBoolean
     * @param bool $parameterWithDefaultValueFalse
     * @param object $objectWithoutNativeTypehint
     * @param object $objectWithNativeTypehint
     * @return Foo
     */
    public function doFoo($mixedParameter, $unionTypeParameter, $anotherMixedParameter, $yetAnotherMixedParameter, $integerParameter, $anotherIntegerParameter, $arrayParameterOne, $arrayParameterOther, $objectRelative, $objectFullyQualified, $objectUsed, $nullableInteger, $nullableObject, $selfType, $staticType, $nullType, $barObject, \_PhpScoperbd5d0c5f7638\MethodPhpDocsNamespace\Bar $conflictedObject, \_PhpScoperbd5d0c5f7638\MethodPhpDocsNamespace\Bar $moreSpecifiedObject, $resource, $yetAnotherAnotherMixedParameter, $yetAnotherAnotherAnotherMixedParameter, $yetAnotherAnotherAnotherAnotherMixedParameter, $voidParameter, $useWithoutAlias, $true, $false, bool $boolTrue, bool $boolFalse, bool $trueBoolean, $objectWithoutNativeTypehint, object $objectWithNativeTypehint, $parameterWithDefaultValueFalse = \false, $anotherNullableObject = null)
    {
        $parent = new \_PhpScoperbd5d0c5f7638\MethodPhpDocsNamespace\FooParent();
        $differentInstance = new self();
        /** @var self $inlineSelf */
        $inlineSelf = doFoo();
        /** @var Bar $inlineBar */
        $inlineBar = doFoo();
        foreach ($moreSpecifiedObject->doFluentUnionIterable() as $fluentUnionIterableBaz) {
            die;
        }
    }
    /**
     * @return self[]
     */
    public function doBar() : array
    {
    }
    public function returnParent() : \_PhpScoperbd5d0c5f7638\parent
    {
    }
    /**
     * @return parent
     */
    public function returnPhpDocParent()
    {
    }
    /**
     * @return NULL[]
     */
    public function returnNulls() : array
    {
    }
    public function returnObject() : object
    {
    }
    public function phpDocVoidMethod() : self
    {
    }
    public function phpDocVoidMethodFromInterface() : self
    {
    }
    public function phpDocVoidParentMethod() : self
    {
    }
    public function phpDocWithoutCurlyBracesVoidParentMethod() : self
    {
    }
    /**
     * @return string[]
     */
    public function returnsStringArray() : array
    {
    }
    private function privateMethodWithPhpDoc()
    {
    }
}
