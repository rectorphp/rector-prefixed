<?php

declare (strict_types=1);
namespace Rector\Defluent\Tests\Rector\Return_\ReturnNewFluentChainMethodCallToNonFluentRector;

use Iterator;
use Rector\Defluent\Rector\Return_\ReturnNewFluentChainMethodCallToNonFluentRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210218\Symplify\SmartFileSystem\SmartFileInfo;
final class ReturnNewFluentChainMethodCallToNonFluentRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
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
        return \Rector\Defluent\Rector\Return_\ReturnNewFluentChainMethodCallToNonFluentRector::class;
    }
}
