<?php

declare (strict_types=1);
namespace Rector\Polyfill\Tests\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector;

use Iterator;
use Rector\Polyfill\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20201230\Symplify\SmartFileSystem\SmartFileInfo;
final class UnwrapFutureCompatibleIfPhpVersionRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20201230\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\Polyfill\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector::class;
    }
}
