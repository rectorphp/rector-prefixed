<?php

declare (strict_types=1);
namespace Rector\CodingStyle\Tests\Rector\Assign\SplitDoubleAssignRector;

use Iterator;
use Rector\CodingStyle\Rector\Assign\SplitDoubleAssignRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210218\Symplify\SmartFileSystem\SmartFileInfo;
final class SplitDoubleAssignRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210218\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\CodingStyle\Rector\Assign\SplitDoubleAssignRector::class;
    }
}
