<?php

declare (strict_types=1);
namespace Rector\Nette\Tests\Rector\NotIdentical\StrposToStringsContainsRector;

use Iterator;
use Rector\Nette\Rector\NotIdentical\StrposToStringsContainsRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210212\Symplify\SmartFileSystem\SmartFileInfo;
final class StrposToStringsContainsRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210212\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\Nette\Rector\NotIdentical\StrposToStringsContainsRector::class;
    }
}
