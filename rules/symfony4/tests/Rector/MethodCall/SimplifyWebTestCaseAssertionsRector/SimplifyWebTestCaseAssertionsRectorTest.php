<?php

declare (strict_types=1);
namespace Rector\Symfony4\Tests\Rector\MethodCall\SimplifyWebTestCaseAssertionsRector;

use Iterator;
use Rector\Symfony4\Rector\MethodCall\SimplifyWebTestCaseAssertionsRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class SimplifyWebTestCaseAssertionsRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\Symfony4\Rector\MethodCall\SimplifyWebTestCaseAssertionsRector::class;
    }
}