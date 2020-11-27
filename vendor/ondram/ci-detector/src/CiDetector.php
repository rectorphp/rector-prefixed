<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\OndraM\CiDetector;

use _PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\CiInterface;
use _PhpScoperbd5d0c5f7638\OndraM\CiDetector\Exception\CiNotDetectedException;
/**
 * Unified way to get environment variables from current continuous integration server
 */
class CiDetector
{
    public const CI_APPVEYOR = 'AppVeyor';
    public const CI_AWS_CODEBUILD = 'AWS CodeBuild';
    public const CI_BAMBOO = 'Bamboo';
    public const CI_BITBUCKET_PIPELINES = 'Bitbucket Pipelines';
    public const CI_BUDDY = 'Buddy';
    public const CI_CIRCLE = 'CircleCI';
    public const CI_CODESHIP = 'Codeship';
    public const CI_CONTINUOUSPHP = 'continuousphp';
    public const CI_DRONE = 'drone';
    public const CI_GITHUB_ACTIONS = 'GitHub Actions';
    public const CI_GITLAB = 'GitLab';
    public const CI_JENKINS = 'Jenkins';
    public const CI_TEAMCITY = 'TeamCity';
    public const CI_TRAVIS = 'Travis CI';
    public const CI_WERCKER = 'Wercker';
    /** @var Env */
    private $environment;
    public function __construct()
    {
        $this->environment = new \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Env();
    }
    public static function fromEnvironment(\_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Env $environment) : self
    {
        $detector = new static();
        $detector->environment = $environment;
        return $detector;
    }
    /**
     * Is current environment an recognized CI server?
     */
    public function isCiDetected() : bool
    {
        $ciServer = $this->detectCurrentCiServer();
        return $ciServer !== null;
    }
    /**
     * Detect current CI server and return instance of its settings
     *
     * @throws CiNotDetectedException
     */
    public function detect() : \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\CiInterface
    {
        $ciServer = $this->detectCurrentCiServer();
        if ($ciServer === null) {
            throw new \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Exception\CiNotDetectedException('No CI server detected in current environment');
        }
        return $ciServer;
    }
    /**
     * @return string[]
     */
    protected function getCiServers() : array
    {
        return [\_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\AppVeyor::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\AwsCodeBuild::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\Bamboo::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\BitbucketPipelines::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\Buddy::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\Circle::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\Codeship::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\Continuousphp::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\Drone::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\GitHubActions::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\GitLab::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\Jenkins::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\TeamCity::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\Travis::class, \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\Wercker::class];
    }
    protected function detectCurrentCiServer() : ?\_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\CiInterface
    {
        $ciServers = $this->getCiServers();
        foreach ($ciServers as $ciClass) {
            $callback = [$ciClass, 'isDetected'];
            if (\is_callable($callback)) {
                if ($callback($this->environment)) {
                    return new $ciClass($this->environment);
                }
            }
        }
        return null;
    }
}