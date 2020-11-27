<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use Rector\Symfony\Rector\ClassMethod\ConsoleExecuteReturnIntRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
# https://github.com/symfony/symfony/blob/4.4/UPGRADE-4.4.md
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    # https://github.com/symfony/symfony/pull/33775
    $services->set(\Rector\Symfony\Rector\ClassMethod\ConsoleExecuteReturnIntRector::class);
};