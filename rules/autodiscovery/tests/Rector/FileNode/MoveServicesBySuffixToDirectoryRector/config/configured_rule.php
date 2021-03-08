<?php

namespace RectorPrefix20210308;

use Rector\Autodiscovery\Rector\FileNode\MoveServicesBySuffixToDirectoryRector;
use RectorPrefix20210308\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210308\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Autodiscovery\Rector\FileNode\MoveServicesBySuffixToDirectoryRector::class)->call('configure', [[\Rector\Autodiscovery\Rector\FileNode\MoveServicesBySuffixToDirectoryRector::GROUP_NAMES_BY_SUFFIX => ['Repository', 'Command', 'Mapper', 'Controller']]]);
};
