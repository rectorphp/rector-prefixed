<?php

declare (strict_types=1);
namespace RectorPrefix20210317\Jean85\Exception;

class ProvidedPackageException extends \Exception implements \RectorPrefix20210317\Jean85\Exception\VersionMissingExceptionInterface
{
    public static function create(string $packageName) : \RectorPrefix20210317\Jean85\Exception\VersionMissingExceptionInterface
    {
        return new self('Cannot retrieve a version for package ' . $packageName . ' since it is provided, probably a metapackage');
    }
}
