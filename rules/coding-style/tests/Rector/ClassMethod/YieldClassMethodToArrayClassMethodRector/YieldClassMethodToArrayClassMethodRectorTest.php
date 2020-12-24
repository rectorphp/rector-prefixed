<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\CodingStyle\Tests\Rector\ClassMethod\YieldClassMethodToArrayClassMethodRector;

use Iterator;
use _PhpScopere8e811afab72\Rector\CodingStyle\Rector\ClassMethod\YieldClassMethodToArrayClassMethodRector;
use _PhpScopere8e811afab72\Rector\CodingStyle\Tests\Rector\ClassMethod\YieldClassMethodToArrayClassMethodRector\Source\EventSubscriberInterface;
use _PhpScopere8e811afab72\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo;
final class YieldClassMethodToArrayClassMethodRectorTest extends \_PhpScopere8e811afab72\Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
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
        return [\_PhpScopere8e811afab72\Rector\CodingStyle\Rector\ClassMethod\YieldClassMethodToArrayClassMethodRector::class => [\_PhpScopere8e811afab72\Rector\CodingStyle\Rector\ClassMethod\YieldClassMethodToArrayClassMethodRector::METHODS_BY_TYPE => [\_PhpScopere8e811afab72\Rector\CodingStyle\Tests\Rector\ClassMethod\YieldClassMethodToArrayClassMethodRector\Source\EventSubscriberInterface::class => ['getSubscribedEvents']]]];
    }
}
