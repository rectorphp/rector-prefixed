<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Generic\Tests\Rector\New_\NewObjectToFactoryCreateRector;

use Iterator;
use _PhpScoperb75b35f52b74\Rector\Generic\Rector\New_\NewObjectToFactoryCreateRector;
use _PhpScoperb75b35f52b74\Rector\Generic\Tests\Rector\New_\NewObjectToFactoryCreateRector\Source\MyClass;
use _PhpScoperb75b35f52b74\Rector\Generic\Tests\Rector\New_\NewObjectToFactoryCreateRector\Source\MyClassFactory;
use _PhpScoperb75b35f52b74\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo;
final class NewObjectToFactoryCreateRectorTest extends \_PhpScoperb75b35f52b74\Rector\Testing\PHPUnit\AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    /**
     * @return array<string, mixed[]>
     */
    protected function getRectorsWithConfiguration() : array
    {
        return [\_PhpScoperb75b35f52b74\Rector\Generic\Rector\New_\NewObjectToFactoryCreateRector::class => [\_PhpScoperb75b35f52b74\Rector\Generic\Rector\New_\NewObjectToFactoryCreateRector::OBJECT_TO_FACTORY_METHOD => [\_PhpScoperb75b35f52b74\Rector\Generic\Tests\Rector\New_\NewObjectToFactoryCreateRector\Source\MyClass::class => ['class' => \_PhpScoperb75b35f52b74\Rector\Generic\Tests\Rector\New_\NewObjectToFactoryCreateRector\Source\MyClassFactory::class, 'method' => 'create']]]];
    }
}
