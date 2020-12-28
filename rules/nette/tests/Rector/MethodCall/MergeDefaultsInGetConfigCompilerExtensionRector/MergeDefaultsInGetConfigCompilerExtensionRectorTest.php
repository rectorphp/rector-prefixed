<?php

declare (strict_types=1);
namespace Rector\Nette\Tests\Rector\MethodCall\MergeDefaultsInGetConfigCompilerExtensionRector;

use Iterator;
use Rector\Nette\Rector\MethodCall\MergeDefaultsInGetConfigCompilerExtensionRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20201228\Symplify\SmartFileSystem\SmartFileInfo;
final class MergeDefaultsInGetConfigCompilerExtensionRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20201228\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\Nette\Rector\MethodCall\MergeDefaultsInGetConfigCompilerExtensionRector::class;
    }
}