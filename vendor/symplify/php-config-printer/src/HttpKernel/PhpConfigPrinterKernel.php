<?php

declare (strict_types=1);
namespace RectorPrefix20210317\Symplify\PhpConfigPrinter\HttpKernel;

use RectorPrefix20210317\Symfony\Component\Config\Loader\LoaderInterface;
use RectorPrefix20210317\Symfony\Component\HttpKernel\Bundle\BundleInterface;
use RectorPrefix20210317\Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\Bundle\PhpConfigPrinterBundle;
use RectorPrefix20210317\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class PhpConfigPrinterKernel extends \RectorPrefix20210317\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel implements \RectorPrefix20210317\Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface
{
    /**
     * @var string[]
     */
    private $configs = [];
    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     */
    public function registerContainerConfiguration($loader) : void
    {
        $loader->load(__DIR__ . '/../../config/config.php');
        foreach ($this->configs as $config) {
            $loader->load($config);
        }
    }
    /**
     * @return BundleInterface[]
     */
    public function registerBundles() : iterable
    {
        return [new \RectorPrefix20210317\Symplify\PhpConfigPrinter\Bundle\PhpConfigPrinterBundle()];
    }
    /**
     * @param string[] $configs
     */
    public function setConfigs($configs) : void
    {
        $this->configs = $configs;
    }
}
