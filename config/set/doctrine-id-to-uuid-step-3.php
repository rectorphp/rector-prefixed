<?php

declare (strict_types=1);
namespace RectorPrefix20201230;

use Rector\Doctrine\Rector\Class_\AddUuidMirrorForRelationPropertyRector;
use RectorPrefix20201230\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20201230\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    # add relations uuid properties
    $services->set(\Rector\Doctrine\Rector\Class_\AddUuidMirrorForRelationPropertyRector::class);
};
