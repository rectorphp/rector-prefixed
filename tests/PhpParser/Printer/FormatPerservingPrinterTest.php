<?php

declare (strict_types=1);
namespace Rector\Core\Tests\PhpParser\Printer;

use Rector\Core\HttpKernel\RectorKernel;
use Rector\Core\PhpParser\Printer\FormatPerservingPrinter;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;
final class FormatPerservingPrinterTest extends \Symplify\PackageBuilder\Testing\AbstractKernelTestCase
{
    /**
     * @var int
     */
    private const EXPECTED_FILEMOD = 0755;
    /**
     * @var FormatPerservingPrinter
     */
    private $formatPerservingPrinter;
    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;
    protected function setUp() : void
    {
        $this->bootKernel(\Rector\Core\HttpKernel\RectorKernel::class);
        $this->formatPerservingPrinter = self::$container->get(\Rector\Core\PhpParser\Printer\FormatPerservingPrinter::class);
        $this->smartFileSystem = self::$container->get(\Symplify\SmartFileSystem\SmartFileSystem::class);
    }
    protected function tearDown() : void
    {
        $this->smartFileSystem->remove(__DIR__ . '/Fixture');
    }
    public function testFileModeIsPreserved() : void
    {
        \mkdir(__DIR__ . '/Fixture');
        \touch(__DIR__ . '/Fixture/file.php');
        \chmod(__DIR__ . '/Fixture/file.php', self::EXPECTED_FILEMOD);
        $fileInfo = new \Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/Fixture/file.php');
        $this->formatPerservingPrinter->printToFile($fileInfo, [], [], []);
        $this->assertSame(self::EXPECTED_FILEMOD, \fileperms(__DIR__ . '/Fixture/file.php') & 0777);
    }
}