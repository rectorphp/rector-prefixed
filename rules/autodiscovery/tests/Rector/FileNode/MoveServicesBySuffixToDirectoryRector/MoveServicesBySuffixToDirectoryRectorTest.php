<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Autodiscovery\Tests\Rector\FileNode\MoveServicesBySuffixToDirectoryRector;

use Iterator;
use _PhpScoperb75b35f52b74\Rector\Autodiscovery\Rector\FileNode\MoveServicesBySuffixToDirectoryRector;
use _PhpScoperb75b35f52b74\Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use _PhpScoperb75b35f52b74\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo;
use _PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileSystem;
final class MoveServicesBySuffixToDirectoryRectorTest extends \_PhpScoperb75b35f52b74\Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo $originalFileInfo, ?\_PhpScoperb75b35f52b74\Rector\FileSystemRector\ValueObject\AddedFileWithContent $expectedAddedFileWithContent) : void
    {
        $this->doTestFileInfo($originalFileInfo);
        if ($expectedAddedFileWithContent === null) {
            // no change - file should have the original location
            $this->assertFileWasNotChanged($this->originalTempFileInfo);
        } else {
            $this->assertFileWithContentWasAdded($expectedAddedFileWithContent);
        }
    }
    public function provideData() : \Iterator
    {
        $smartFileSystem = new \_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileSystem();
        (yield [new \_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/Source/Entity/AppleRepository.php'), new \_PhpScoperb75b35f52b74\Rector\FileSystemRector\ValueObject\AddedFileWithContent($this->getFixtureTempDirectory() . '/Source/Repository/AppleRepository.php', $smartFileSystem->readFile(__DIR__ . '/Expected/Repository/ExpectedAppleRepository.php'))]);
        (yield [new \_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/Source/Controller/BananaCommand.php'), new \_PhpScoperb75b35f52b74\Rector\FileSystemRector\ValueObject\AddedFileWithContent($this->getFixtureTempDirectory() . '/Source/Command/BananaCommand.php', $smartFileSystem->readFile(__DIR__ . '/Expected/Command/ExpectedBananaCommand.php'))]);
        (yield [new \_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/Source/Command/MissPlacedController.php'), new \_PhpScoperb75b35f52b74\Rector\FileSystemRector\ValueObject\AddedFileWithContent($this->getFixtureTempDirectory() . '/Source/Controller/MissPlacedController.php', $smartFileSystem->readFile(__DIR__ . '/Expected/Controller/MissPlacedController.php'))]);
        // nothing changes
        (yield [new \_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/Source/Mapper/SkipCorrectMapper.php'), null]);
        (yield [new \_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/Source/Controller/Nested/AbstractBaseWithSpaceMapper.php'), new \_PhpScoperb75b35f52b74\Rector\FileSystemRector\ValueObject\AddedFileWithContent($this->getFixtureTempDirectory() . '/Source/Mapper/Nested/AbstractBaseWithSpaceMapper.php', $smartFileSystem->readFile(__DIR__ . '/Expected/Mapper/Nested/AbstractBaseWithSpaceMapper.php.inc'))]);
        // inversed order, but should have the same effect
        (yield [new \_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/Source/Entity/UserMapper.php'), new \_PhpScoperb75b35f52b74\Rector\FileSystemRector\ValueObject\AddedFileWithContent($this->getFixtureTempDirectory() . '/Source/Mapper/UserMapper.php', $smartFileSystem->readFile(__DIR__ . '/Expected/Mapper/UserMapper.php.inc'))]);
    }
    /**
     * @return array<string, mixed[]>
     */
    protected function getRectorsWithConfiguration() : array
    {
        return [\_PhpScoperb75b35f52b74\Rector\Autodiscovery\Rector\FileNode\MoveServicesBySuffixToDirectoryRector::class => [\_PhpScoperb75b35f52b74\Rector\Autodiscovery\Rector\FileNode\MoveServicesBySuffixToDirectoryRector::GROUP_NAMES_BY_SUFFIX => ['Repository', 'Command', 'Mapper', 'Controller']]];
    }
}
