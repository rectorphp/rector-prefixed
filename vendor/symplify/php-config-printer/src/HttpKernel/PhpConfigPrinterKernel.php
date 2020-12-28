<?php

declare (strict_types=1);
namespace RectorPrefix20201228\Symplify\PhpConfigPrinter\HttpKernel;

use RectorPrefix20201228\Symfony\Component\Config\Loader\LoaderInterface;
use RectorPrefix20201228\Symfony\Component\HttpKernel\Bundle\BundleInterface;
use RectorPrefix20201228\Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface;
use RectorPrefix20201228\Symplify\PhpConfigPrinter\Bundle\PhpConfigPrinterBundle;
use RectorPrefix20201228\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class PhpConfigPrinterKernel extends \RectorPrefix20201228\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel implements \RectorPrefix20201228\Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface
{
    /**
     * @var string[]
     */
    private $configs = [];
    public function registerContainerConfiguration(\RectorPrefix20201228\Symfony\Component\Config\Loader\LoaderInterface $loader) : void
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
        return [new \RectorPrefix20201228\Symplify\PhpConfigPrinter\Bundle\PhpConfigPrinterBundle()];
    }
    /**
     * @param string[] $configs
     */
    public function setConfigs(array $configs) : void
    {
        $this->configs = $configs;
    }
}
