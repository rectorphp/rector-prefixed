<?php

declare (strict_types=1);
namespace _PhpScoper17db12703726;

use Rector\Doctrine\Rector\Class_\AddUuidMirrorForRelationPropertyRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    # add relations uuid properties
    $services->set(\Rector\Doctrine\Rector\Class_\AddUuidMirrorForRelationPropertyRector::class);
};
