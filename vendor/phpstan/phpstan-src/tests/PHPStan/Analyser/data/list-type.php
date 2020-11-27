<?php

namespace _PhpScoperbd5d0c5f7638\ListType;

use function PHPStan\Analyser\assertType;
class Foo
{
    /** @param list $list */
    public function directAssertion($list) : void
    {
        \PHPStan\Analyser\assertType('array<int, mixed>', $list);
    }
    /** @param list $list */
    public function directAssertionParamHint(array $list) : void
    {
        \PHPStan\Analyser\assertType('array<int, mixed>', $list);
    }
    /** @param list $list */
    public function directAssertionNullableParamHint(array $list = null) : void
    {
        \PHPStan\Analyser\assertType('array<int, mixed>|null', $list);
    }
    /** @param list<\DateTime> $list */
    public function directAssertionObjectParamHint($list) : void
    {
        \PHPStan\Analyser\assertType('array<int, DateTime>', $list);
    }
    public function withoutGenerics() : void
    {
        /** @var list $list */
        $list = [];
        $list[] = '1';
        $list[] = \true;
        $list[] = new \stdClass();
        \PHPStan\Analyser\assertType('array<int, mixed>&nonEmpty', $list);
    }
    public function withMixedType() : void
    {
        /** @var list<mixed> $list */
        $list = [];
        $list[] = '1';
        $list[] = \true;
        $list[] = new \stdClass();
        \PHPStan\Analyser\assertType('array<int, mixed>&nonEmpty', $list);
    }
    public function withObjectType() : void
    {
        /** @var list<\DateTime> $list */
        $list = [];
        $list[] = new \DateTime();
        \PHPStan\Analyser\assertType('array<int, DateTime>&nonEmpty', $list);
    }
    /** @return list<scalar> */
    public function withScalarGoodContent() : void
    {
        /** @var list<scalar> $list */
        $list = [];
        $list[] = '1';
        $list[] = \true;
        \PHPStan\Analyser\assertType('array<int, bool|float|int|string>&nonEmpty', $list);
    }
    public function withNumericKey() : void
    {
        /** @var list $list */
        $list = [];
        $list[] = '1';
        $list['1'] = \true;
        \PHPStan\Analyser\assertType('array<int, mixed>&nonEmpty', $list);
    }
    public function withFullListFunctionality() : void
    {
        // These won't output errors for now but should when list type will be fully implemented
        /** @var list $list */
        $list = [];
        $list[] = '1';
        $list[] = '2';
        unset($list[0]);
        //break list behaviour
        \PHPStan\Analyser\assertType('array<int, mixed>', $list);
        /** @var list $list2 */
        $list2 = [];
        $list2[2] = '1';
        //Most likely to create a gap in indexes
        \PHPStan\Analyser\assertType('array<int, mixed>&nonEmpty', $list2);
    }
}
