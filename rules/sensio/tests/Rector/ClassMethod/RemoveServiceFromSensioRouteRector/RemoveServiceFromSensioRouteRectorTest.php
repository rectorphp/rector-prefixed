<?php

declare (strict_types=1);
namespace Rector\Sensio\Tests\Rector\ClassMethod\RemoveServiceFromSensioRouteRector;

use Iterator;
use Rector\Sensio\Rector\ClassMethod\RemoveServiceFromSensioRouteRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20201230\Symplify\SmartFileSystem\SmartFileInfo;
final class RemoveServiceFromSensioRouteRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
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
        return \Rector\Sensio\Rector\ClassMethod\RemoveServiceFromSensioRouteRector::class;
    }
}
