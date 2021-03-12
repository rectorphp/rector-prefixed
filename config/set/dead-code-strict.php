<?php

declare (strict_types=1);
namespace RectorPrefix20210312;

use Rector\DeadCode\Rector\Class_\RemoveEmptyAbstractClassRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodRector;
use RectorPrefix20210312\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210312\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodRector::class);
    $services->set(\Rector\DeadCode\Rector\Class_\RemoveEmptyAbstractClassRector::class);
};
