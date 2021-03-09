<?php

declare (strict_types=1);
namespace RectorPrefix20210309;

use Rector\Autodiscovery\Rector\FileNode\MoveInterfacesToContractNamespaceDirectoryRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use RectorPrefix20210309\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210309\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(\Rector\Core\Configuration\Option::PHP_VERSION_FEATURES, \Rector\Core\ValueObject\PhpVersionFeature::TYPED_PROPERTIES - 1);
    $services = $containerConfigurator->services();
    $services->set(\Rector\Autodiscovery\Rector\FileNode\MoveInterfacesToContractNamespaceDirectoryRector::class);
};
