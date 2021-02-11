<?php

declare (strict_types=1);
namespace Rector\DeadDocBlock\Tests\Rector\ClassLike\RemoveAnnotationRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210211\Symplify\SmartFileSystem\SmartFileInfo;
final class RemoveAnnotationRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210211\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function provideConfigFileInfo() : ?\RectorPrefix20210211\Symplify\SmartFileSystem\SmartFileInfo
    {
        return new \RectorPrefix20210211\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/config/configured_rule.php');
    }
}
