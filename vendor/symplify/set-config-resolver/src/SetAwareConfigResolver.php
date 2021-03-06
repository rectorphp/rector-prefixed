<?php

declare (strict_types=1);
namespace RectorPrefix20210317\Symplify\SetConfigResolver;

use RectorPrefix20210317\Symplify\SetConfigResolver\Config\SetsParameterResolver;
use RectorPrefix20210317\Symplify\SetConfigResolver\Contract\SetProviderInterface;
use RectorPrefix20210317\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\SetConfigResolver\Tests\ConfigResolver\SetAwareConfigResolverTest
 */
final class SetAwareConfigResolver extends \RectorPrefix20210317\Symplify\SetConfigResolver\AbstractConfigResolver
{
    /**
     * @var SetsParameterResolver
     */
    private $setsParameterResolver;
    /**
     * @var SetResolver
     */
    private $setResolver;
    /**
     * @param \Symplify\SetConfigResolver\Contract\SetProviderInterface $setProvider
     */
    public function __construct($setProvider)
    {
        $this->setResolver = new \RectorPrefix20210317\Symplify\SetConfigResolver\SetResolver($setProvider);
        $this->setsParameterResolver = new \RectorPrefix20210317\Symplify\SetConfigResolver\Config\SetsParameterResolver($this->setResolver);
        parent::__construct();
    }
    /**
     * @param SmartFileInfo[] $fileInfos
     * @return SmartFileInfo[]
     */
    public function resolveFromParameterSetsFromConfigFiles($fileInfos) : array
    {
        return $this->setsParameterResolver->resolveFromFileInfos($fileInfos);
    }
}
