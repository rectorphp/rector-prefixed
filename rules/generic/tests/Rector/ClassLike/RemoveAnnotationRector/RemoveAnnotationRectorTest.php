<?php

declare (strict_types=1);
namespace Rector\Generic\Tests\Rector\ClassLike\RemoveAnnotationRector;

use Iterator;
use Rector\BetterPhpDocParser\ValueObject\PhpDocNode\JMS\JMSInjectParamsTagValueNode;
use Rector\Generic\Rector\ClassLike\RemoveAnnotationRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210127\Symplify\SmartFileSystem\SmartFileInfo;
final class RemoveAnnotationRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210127\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
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
        return [\Rector\Generic\Rector\ClassLike\RemoveAnnotationRector::class => [\Rector\Generic\Rector\ClassLike\RemoveAnnotationRector::ANNOTATIONS_TO_REMOVE => ['method', \Rector\BetterPhpDocParser\ValueObject\PhpDocNode\JMS\JMSInjectParamsTagValueNode::class]]];
    }
}
