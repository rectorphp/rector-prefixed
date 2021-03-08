<?php

declare (strict_types=1);
namespace RectorPrefix20210308;

use Rector\Doctrine\Rector\Class_\AlwaysInitializeUuidInEntityRector;
use RectorPrefix20210308\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210308\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Doctrine\Rector\Class_\AlwaysInitializeUuidInEntityRector::class);
};
