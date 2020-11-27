<?php

declare (strict_types=1);
namespace Symplify\ComposerJsonManipulator\Tests\Sorter;

use Iterator;
use Symplify\ComposerJsonManipulator\Sorter\ComposerPackageSorter;
use Symplify\ComposerJsonManipulator\Tests\HttpKernel\ComposerJsonManipulatorKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
final class ComposerPackageSorterTest extends \Symplify\PackageBuilder\Testing\AbstractKernelTestCase
{
    /**
     * @var ComposerPackageSorter
     */
    private $composerPackageSorter;
    protected function setUp() : void
    {
        $this->bootKernel(\Symplify\ComposerJsonManipulator\Tests\HttpKernel\ComposerJsonManipulatorKernel::class);
        $this->composerPackageSorter = self::$container->get(\Symplify\ComposerJsonManipulator\Sorter\ComposerPackageSorter::class);
    }
    /**
     * @dataProvider provideData()
     */
    public function test(array $packages, array $expectedSortedPackages) : void
    {
        $sortedPackages = $this->composerPackageSorter->sortPackages($packages);
        $this->assertSame($expectedSortedPackages, $sortedPackages);
    }
    public function provideData() : \Iterator
    {
        (yield [['symfony/console' => '^5.2', 'php' => '^8.0', 'ext-json' => '*'], ['php' => '^8.0', 'ext-json' => '*', 'symfony/console' => '^5.2']]);
    }
}