<?php

declare (strict_types=1);
namespace Rector\Php53\Tests\Rector\AssignRef\ClearReturnNewByReferenceRector;

use Iterator;
use Rector\Php53\Rector\AssignRef\ClearReturnNewByReferenceRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SplFileInfo;
use Symplify\SmartFileSystem\SmartFileInfo;
final class ClearReturnNewByReferenceRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfoWithoutAutoload($fileInfo);
    }
    /**
     * @return Iterator<SplFileInfo>
     */
    public function provideDataForTest() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\Php53\Rector\AssignRef\ClearReturnNewByReferenceRector::class;
    }
}