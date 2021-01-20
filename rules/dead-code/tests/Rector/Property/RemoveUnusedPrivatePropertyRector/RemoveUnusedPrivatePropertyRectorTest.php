<?php

declare (strict_types=1);
namespace Rector\DeadCode\Tests\Rector\Property\RemoveUnusedPrivatePropertyRector;

use Iterator;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210120\Symplify\SmartFileSystem\SmartFileInfo;
final class RemoveUnusedPrivatePropertyRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210120\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector::class;
    }
}
