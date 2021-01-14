<?php

declare (strict_types=1);
namespace RectorPrefix20210114\Symplify\Astral\DependencyInjection\Extension;

use RectorPrefix20210114\Symfony\Component\Config\FileLocator;
use RectorPrefix20210114\Symfony\Component\DependencyInjection\ContainerBuilder;
use RectorPrefix20210114\Symfony\Component\DependencyInjection\Extension\Extension;
use RectorPrefix20210114\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
final class AstralExtension extends \RectorPrefix20210114\Symfony\Component\DependencyInjection\Extension\Extension
{
    public function load(array $configs, \RectorPrefix20210114\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $phpFileLoader = new \RectorPrefix20210114\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, new \RectorPrefix20210114\Symfony\Component\Config\FileLocator(__DIR__ . '/../../../config'));
        $phpFileLoader->load('config.php');
    }
}