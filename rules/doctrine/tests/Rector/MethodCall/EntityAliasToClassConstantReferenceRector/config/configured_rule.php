<?php

namespace RectorPrefix20210215;

use Rector\Doctrine\Rector\MethodCall\EntityAliasToClassConstantReferenceRector;
use RectorPrefix20210215\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210215\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Doctrine\Rector\MethodCall\EntityAliasToClassConstantReferenceRector::class)->call('configure', [[\Rector\Doctrine\Rector\MethodCall\EntityAliasToClassConstantReferenceRector::ALIASES_TO_NAMESPACES => ['App' => 'App\\Entity']]]);
};
