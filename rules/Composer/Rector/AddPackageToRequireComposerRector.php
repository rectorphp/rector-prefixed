<?php

declare (strict_types=1);
namespace Rector\Composer\Rector;

use Rector\Composer\Contract\Rector\ComposerRectorInterface;
use Rector\Composer\Guard\VersionGuard;
use Rector\Composer\ValueObject\PackageAndVersion;
use RectorPrefix20210317\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\Composer\Rector\AddPackageToRequireComposerRector\AddPackageToRequireComposerRectorTest
 */
final class AddPackageToRequireComposerRector implements \Rector\Composer\Contract\Rector\ComposerRectorInterface
{
    /**
     * @var string
     */
    public const PACKAGES_AND_VERSIONS = 'packages_and_versions';
    /**
     * @var PackageAndVersion[]
     */
    private $packagesAndVersions = [];
    /**
     * @var VersionGuard
     */
    private $versionGuard;
    /**
     * @param \Rector\Composer\Guard\VersionGuard $versionGuard
     */
    public function __construct($versionGuard)
    {
        $this->versionGuard = $versionGuard;
    }
    /**
     * @param \Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson
     */
    public function refactor($composerJson) : void
    {
        foreach ($this->packagesAndVersions as $packageAndVersion) {
            $composerJson->addRequiredPackage($packageAndVersion->getPackageName(), $packageAndVersion->getVersion());
        }
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Add package to "require" in `composer.json`', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
{
    "require": {
        "symfony/console": "^3.4"
    }
}
CODE_SAMPLE
, [self::PACKAGES_AND_VERSIONS => [new \Rector\Composer\ValueObject\PackageAndVersion('symfony/console', '^3.4')]])]);
    }
    /**
     * @param array<string, PackageAndVersion[]> $configuration
     */
    public function configure($configuration) : void
    {
        $packagesAndVersions = $configuration[self::PACKAGES_AND_VERSIONS] ?? [];
        $this->versionGuard->validate($packagesAndVersions);
        $this->packagesAndVersions = $packagesAndVersions;
    }
}
