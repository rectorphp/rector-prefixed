<?php

declare (strict_types=1);
namespace Rector\DowngradePhp72\Tests\Rector\FunctionLike\DowngradeReturnObjectTypeDeclarationRector;

use Iterator;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DowngradePhp72\Rector\FunctionLike\DowngradeReturnObjectTypeDeclarationRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class DowngradeReturnObjectTypeDeclarationRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @requires PHP 7.2
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
    /**
     * @return array<string, mixed[]>
     */
    protected function getRectorsWithConfiguration() : array
    {
        return [\Rector\DowngradePhp72\Rector\FunctionLike\DowngradeReturnObjectTypeDeclarationRector::class => [\Rector\DowngradePhp72\Rector\FunctionLike\DowngradeReturnObjectTypeDeclarationRector::ADD_DOC_BLOCK => \true]];
    }
    protected function getRectorClass() : string
    {
        return \Rector\DowngradePhp72\Rector\FunctionLike\DowngradeReturnObjectTypeDeclarationRector::class;
    }
    protected function getPhpVersion() : int
    {
        return \Rector\Core\ValueObject\PhpVersionFeature::OBJECT_TYPE - 1;
    }
}