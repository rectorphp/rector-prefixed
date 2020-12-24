<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Php70\Tests\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Source;

class WithOnlyStaticMethods
{
    public static function aBoolMethod() : bool
    {
        return \true;
    }
    public static function aStringMethod() : string
    {
        return 'yeah';
    }
}
