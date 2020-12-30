<?php

declare (strict_types=1);
namespace RectorPrefix20201230;

use Rector\PHPUnit\Rector\MethodCall\UseSpecificWillMethodRector;
use RectorPrefix20201230\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20201230\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\PHPUnit\Rector\MethodCall\UseSpecificWillMethodRector::class);
};
