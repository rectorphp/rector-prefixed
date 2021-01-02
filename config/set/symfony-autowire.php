<?php

declare (strict_types=1);
namespace RectorPrefix20210102;

use Rector\Symfony\Rector\ClassMethod\NormalizeAutowireMethodNamingRector;
use RectorPrefix20210102\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210102\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Symfony\Rector\ClassMethod\NormalizeAutowireMethodNamingRector::class);
};
