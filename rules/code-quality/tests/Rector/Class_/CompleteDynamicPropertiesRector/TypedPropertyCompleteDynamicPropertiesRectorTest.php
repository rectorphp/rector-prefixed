<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\CodeQuality\Tests\Rector\Class_\CompleteDynamicPropertiesRector;

use Iterator;
use _PhpScoperb75b35f52b74\Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use _PhpScoperb75b35f52b74\Rector\Core\ValueObject\PhpVersionFeature;
use _PhpScoperb75b35f52b74\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo;
final class TypedPropertyCompleteDynamicPropertiesRectorTest extends \_PhpScoperb75b35f52b74\Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureTypedProperty');
    }
    protected function getRectorClass() : string
    {
        return \_PhpScoperb75b35f52b74\Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector::class;
    }
    protected function getPhpVersion() : int
    {
        return \_PhpScoperb75b35f52b74\Rector\Core\ValueObject\PhpVersionFeature::TYPED_PROPERTIES;
    }
}
