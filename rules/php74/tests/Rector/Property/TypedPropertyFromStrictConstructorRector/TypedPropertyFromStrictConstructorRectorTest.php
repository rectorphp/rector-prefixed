<?php

declare (strict_types=1);
namespace Rector\Php74\Tests\Rector\Property\TypedPropertyFromStrictConstructorRector;

use Iterator;
use Rector\Php74\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210126\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @requires PHP 7.4
 */
final class TypedPropertyFromStrictConstructorRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210126\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\Php74\Rector\Property\TypedPropertyFromStrictConstructorRector::class;
    }
}