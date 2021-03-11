<?php

declare (strict_types=1);
namespace RectorPrefix20210311\Symplify\ComposerJsonManipulator\Tests\ValueObject;

use RectorPrefix20210311\PHPUnit\Framework\TestCase;
use RectorPrefix20210311\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
final class ComposerJsonTest extends \RectorPrefix20210311\PHPUnit\Framework\TestCase
{
    public function testSorting() : void
    {
        $composerJson = new \RectorPrefix20210311\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->addRequiredPackage('symfony/console', '^5.5');
        $composerJson->addRequiredPackage('nette/utils', '^3.2');
        $this->assertSame(['symfony/console' => '^5.5', 'nette/utils' => '^3.2'], $composerJson->getRequire());
    }
    public function testMovePackageToRequireDev() : void
    {
        $composerJson = new \RectorPrefix20210311\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->addRequiredPackage('symfony/console', '^5.5');
        $composerJson->addRequiredDevPackage('symfony/http-kernel', '^5.5');
        $composerJson->movePackageToRequireDev('symfony/console');
        $this->assertSame(['symfony/http-kernel' => '^5.5', 'symfony/console' => '^5.5'], $composerJson->getRequireDev());
    }
    public function testReplacePacage() : void
    {
        $composerJson = new \RectorPrefix20210311\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->addRequiredPackage('symfony/console', '^5.5');
        $composerJson->replacePackage('symfony/console', 'symfony/http-kernel', '^5.0');
        $this->assertSame(['symfony/http-kernel' => '^5.0'], $composerJson->getRequire());
    }
}
