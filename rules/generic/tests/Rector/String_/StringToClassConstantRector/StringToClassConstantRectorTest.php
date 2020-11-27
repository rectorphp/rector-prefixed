<?php

declare (strict_types=1);
namespace Rector\Generic\Tests\Rector\String_\StringToClassConstantRector;

use Iterator;
use Rector\Generic\Rector\String_\StringToClassConstantRector;
use Rector\Generic\ValueObject\StringToClassConstant;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class StringToClassConstantRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
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
    /**
     * @return array<string, mixed[]>
     */
    protected function getRectorsWithConfiguration() : array
    {
        return [\Rector\Generic\Rector\String_\StringToClassConstantRector::class => [\Rector\Generic\Rector\String_\StringToClassConstantRector::STRINGS_TO_CLASS_CONSTANTS => [new \Rector\Generic\ValueObject\StringToClassConstant('compiler.post_dump', '_PhpScoperbd5d0c5f7638\\Yet\\AnotherClass', 'CONSTANT'), new \Rector\Generic\ValueObject\StringToClassConstant('compiler.to_class', '_PhpScoperbd5d0c5f7638\\Yet\\AnotherClass', 'class')]]];
    }
}