<?php

declare (strict_types=1);
namespace Rector\CodeQuality\Tests\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector;

use Iterator;
use Rector\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210122\Symplify\SmartFileSystem\SmartFileInfo;
final class BooleanNotIdenticalToNotIdenticalRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210122\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector::class;
    }
}
