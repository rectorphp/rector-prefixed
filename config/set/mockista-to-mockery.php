<?php

declare (strict_types=1);
namespace _PhpScoper5b8c9e9ebd21;

use Rector\MockistaToMockery\Rector\Class_\MockeryTearDownRector;
use Rector\MockistaToMockery\Rector\ClassMethod\MockistaMockToMockeryMockRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\MockistaToMockery\Rector\Class_\MockeryTearDownRector::class);
    $services->set(\Rector\MockistaToMockery\Rector\ClassMethod\MockistaMockToMockeryMockRector::class);
};
