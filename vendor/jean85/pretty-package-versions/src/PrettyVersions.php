<?php

declare (strict_types=1);
namespace RectorPrefix20210314\Jean85;

use RectorPrefix20210314\Composer\InstalledVersions;
use RectorPrefix20210314\Jean85\Exception\ProvidedPackageException;
use RectorPrefix20210314\Jean85\Exception\ReplacedPackageException;
use RectorPrefix20210314\Jean85\Exception\VersionMissingExceptionInterface;
class PrettyVersions
{
    /**
     * @throws VersionMissingExceptionInterface When a package is provided ({@see ProvidedPackageException}) or replaced ({@see ReplacedPackageException})
     */
    public static function getVersion(string $packageName) : \RectorPrefix20210314\Jean85\Version
    {
        if (isset(\RectorPrefix20210314\Composer\InstalledVersions::getRawData()['versions'][$packageName]['provided'])) {
            throw \RectorPrefix20210314\Jean85\Exception\ProvidedPackageException::create($packageName);
        }
        if (isset(\RectorPrefix20210314\Composer\InstalledVersions::getRawData()['versions'][$packageName]['replaced'])) {
            throw \RectorPrefix20210314\Jean85\Exception\ReplacedPackageException::create($packageName);
        }
        return new \RectorPrefix20210314\Jean85\Version($packageName, \RectorPrefix20210314\Composer\InstalledVersions::getPrettyVersion($packageName), \RectorPrefix20210314\Composer\InstalledVersions::getReference($packageName));
    }
    public static function getRootPackageName() : string
    {
        return \RectorPrefix20210314\Composer\InstalledVersions::getRootPackage()['name'];
    }
    public static function getRootPackageVersion() : \RectorPrefix20210314\Jean85\Version
    {
        return new \RectorPrefix20210314\Jean85\Version(self::getRootPackageName(), \RectorPrefix20210314\Composer\InstalledVersions::getRootPackage()['pretty_version'], \RectorPrefix20210314\Composer\InstalledVersions::getRootPackage()['reference']);
    }
}
