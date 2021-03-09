<?php

declare (strict_types=1);
namespace Rector\CodeQuality\Tests\Rector\Identical\GetClassToInstanceOfRector;

use Iterator;
use Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210309\Symplify\SmartFileSystem\SmartFileInfo;
final class GetClassToInstanceOfRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210309\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector::class;
    }
}
