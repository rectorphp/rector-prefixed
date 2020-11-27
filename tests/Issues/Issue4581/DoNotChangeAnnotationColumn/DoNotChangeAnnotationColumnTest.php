<?php

declare (strict_types=1);
namespace Rector\Core\Tests\Issues\Issue4581\DoNotChangeAnnotationColumn;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class DoNotChangeAnnotationColumnTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @requires PHP 7.4
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
    // bin/rector process ... --config config/some_config.php
    protected function provideConfigFileInfo() : \Symplify\SmartFileSystem\SmartFileInfo
    {
        return new \Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/config/some_config.php');
    }
}