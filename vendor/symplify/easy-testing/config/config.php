<?php

declare (strict_types=1);
namespace RectorPrefix20210108;

use RectorPrefix20210108\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210108\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('RectorPrefix20210108\Symplify\\EasyTesting\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/DataProvider', __DIR__ . '/../src/HttpKernel', __DIR__ . '/../src/ValueObject']);
};