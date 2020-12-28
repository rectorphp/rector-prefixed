<?php

declare (strict_types=1);
namespace Rector\Transform\Tests\Rector\StaticCall\StaticCallToMethodCallRector\Source;

use RectorPrefix20201228\Symplify\SmartFileSystem\SmartFileSystem;
abstract class ClassWithFileSystemMethod
{
    public function getSmartFileSystem() : \RectorPrefix20201228\Symplify\SmartFileSystem\SmartFileSystem
    {
        return new \RectorPrefix20201228\Symplify\SmartFileSystem\SmartFileSystem();
    }
}
