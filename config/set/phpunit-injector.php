<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use Rector\PHPUnit\Rector\Class_\SelfContainerGetMethodCallFromTestToInjectPropertyRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\PHPUnit\Rector\Class_\SelfContainerGetMethodCallFromTestToInjectPropertyRector::class);
};
