<?php

declare (strict_types=1);
namespace Rector\CodingStyle\Tests\Rector\Namespace_\ImportFullyQualifiedNamesRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210212\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Rector\PostRector\Rector\NameImportingPostRector
 */
final class Php80Test extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @requires PHP 8.0
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210212\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAttributes');
    }
    protected function provideConfigFileInfo() : ?\RectorPrefix20210212\Symplify\SmartFileSystem\SmartFileInfo
    {
        return new \RectorPrefix20210212\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/config/import_config.php');
    }
}
