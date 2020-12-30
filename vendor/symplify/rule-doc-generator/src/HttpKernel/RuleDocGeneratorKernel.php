<?php

declare (strict_types=1);
namespace Symplify\RuleDocGenerator\HttpKernel;

use RectorPrefix20201230\Symfony\Component\Config\Loader\LoaderInterface;
use RectorPrefix20201230\Symfony\Component\HttpKernel\Bundle\BundleInterface;
use RectorPrefix20201230\Symplify\MarkdownDiff\Bundle\MarkdownDiffBundle;
use RectorPrefix20201230\Symplify\PhpConfigPrinter\Bundle\PhpConfigPrinterBundle;
use RectorPrefix20201230\Symplify\SymplifyKernel\Bundle\SymplifyKernelBundle;
use RectorPrefix20201230\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class RuleDocGeneratorKernel extends \RectorPrefix20201230\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    public function registerContainerConfiguration(\RectorPrefix20201230\Symfony\Component\Config\Loader\LoaderInterface $loader) : void
    {
        $loader->load(__DIR__ . '/../../config/config.php');
        parent::registerContainerConfiguration($loader);
    }
    /**
     * @return BundleInterface[]
     */
    public function registerBundles() : iterable
    {
        return [new \RectorPrefix20201230\Symplify\SymplifyKernel\Bundle\SymplifyKernelBundle(), new \RectorPrefix20201230\Symplify\MarkdownDiff\Bundle\MarkdownDiffBundle(), new \RectorPrefix20201230\Symplify\PhpConfigPrinter\Bundle\PhpConfigPrinterBundle()];
    }
}
