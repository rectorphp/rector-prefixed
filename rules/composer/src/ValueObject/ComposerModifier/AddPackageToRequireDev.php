<?php

declare (strict_types=1);
namespace Rector\Composer\ValueObject\ComposerModifier;

use Rector\Composer\Contract\ComposerModifier\ComposerModifierInterface;
use Rector\Composer\ValueObject\Version\Version;
use RectorPrefix20210113\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
/**
 * Only adds package to require-dev section, if package is already in composer data, nothing happen
 * @see \Rector\Composer\Tests\ValueObject\ComposerModifier\AddPackageToRequireDevTest
 */
final class AddPackageToRequireDev implements \Rector\Composer\Contract\ComposerModifier\ComposerModifierInterface
{
    /**
     * @var string
     */
    private $packageName;
    /**
     * @var Version
     */
    private $version;
    /**
     * @param string $packageName name of package (vendor/package)
     * @param string $version target package version (1.2.3, ^1.2, ~1.2.3 etc.)
     */
    public function __construct(string $packageName, string $version)
    {
        $this->packageName = $packageName;
        $this->version = new \Rector\Composer\ValueObject\Version\Version($version);
    }
    public function modify(\RectorPrefix20210113\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : \RectorPrefix20210113\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $composerJson->addRequiredDevPackage($this->packageName, $this->version->getVersion());
        return $composerJson;
    }
}
