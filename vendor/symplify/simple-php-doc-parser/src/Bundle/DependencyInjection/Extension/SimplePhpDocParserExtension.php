<?php

declare (strict_types=1);
namespace Symplify\SimplePhpDocParser\Bundle\DependencyInjection\Extension;

use _PhpScoper267b3276efc2\Symfony\Component\Config\FileLocator;
use _PhpScoper267b3276efc2\Symfony\Component\DependencyInjection\ContainerBuilder;
use _PhpScoper267b3276efc2\Symfony\Component\DependencyInjection\Extension\Extension;
use _PhpScoper267b3276efc2\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
final class SimplePhpDocParserExtension extends \_PhpScoper267b3276efc2\Symfony\Component\DependencyInjection\Extension\Extension
{
    public function load(array $configs, \_PhpScoper267b3276efc2\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $phpFileLoader = new \_PhpScoper267b3276efc2\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, new \_PhpScoper267b3276efc2\Symfony\Component\Config\FileLocator(__DIR__ . '/../../../../config'));
        $phpFileLoader->load('config.php');
    }
}
