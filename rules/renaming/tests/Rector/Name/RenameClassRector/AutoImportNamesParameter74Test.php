<?php

declare (strict_types=1);
namespace Rector\Renaming\Tests\Rector\Name\RenameClassRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210211\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Rector\PostRector\Rector\NameImportingPostRector
 */
final class AutoImportNamesParameter74Test extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     * @requires PHP 7.4
     */
    public function test(\RectorPrefix20210211\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAutoImportNames74');
    }
    protected function provideConfigFileInfo() : ?\RectorPrefix20210211\Symplify\SmartFileSystem\SmartFileInfo
    {
        return new \RectorPrefix20210211\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/config/auto_import_names.php');
    }
}
