<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use Rector\NetteToSymfony\Rector\MethodCall\NetteFormToSymfonyFormRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\NetteToSymfony\Rector\MethodCall\NetteFormToSymfonyFormRector::class);
};