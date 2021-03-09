<?php

namespace RectorPrefix20210309;

use Rector\Privatization\Rector\ClassMethod\PrivatizeLocalOnlyMethodRector;
use RectorPrefix20210309\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210309\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Privatization\Rector\ClassMethod\PrivatizeLocalOnlyMethodRector::class);
};
