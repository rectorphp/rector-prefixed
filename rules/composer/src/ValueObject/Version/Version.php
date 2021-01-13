<?php

namespace Rector\Composer\ValueObject\Version;

use RectorPrefix20210113\Composer\Semver\VersionParser;
use UnexpectedValueException;
final class Version
{
    /** @var string */
    private $version;
    /**
     * @param string $version version string
     * @throws UnexpectedValueException if $version string is not valid
     */
    public function __construct(string $version)
    {
        $versionParser = new \RectorPrefix20210113\Composer\Semver\VersionParser();
        $versionParser->parseConstraints($version);
        $this->version = $version;
    }
    public function getVersion() : string
    {
        return $this->version;
    }
}