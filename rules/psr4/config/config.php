<?php

declare (strict_types=1);
namespace RectorPrefix20201227;

use Rector\PSR4\Composer\PSR4NamespaceMatcher;
use Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface;
use RectorPrefix20201227\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20201227\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('RectorPrefix20201227\Rector\\PSR4\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Rector']);
    $services->alias(\Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface::class, \Rector\PSR4\Composer\PSR4NamespaceMatcher::class);
};
