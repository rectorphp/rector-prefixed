<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72;

use _PhpScopere8e811afab72\Rector\Symfony2\Rector\MethodCall\AddFlashRector;
use _PhpScopere8e811afab72\Rector\Symfony2\Rector\MethodCall\RedirectToRouteRector;
use _PhpScopere8e811afab72\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\_PhpScopere8e811afab72\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScopere8e811afab72\Rector\Symfony2\Rector\MethodCall\RedirectToRouteRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\Symfony2\Rector\MethodCall\AddFlashRector::class);
};
