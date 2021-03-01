<?php

declare (strict_types=1);
namespace RectorPrefix20210301;

use Rector\Core\Configuration\Option;
use Rector\Renaming\Rector\Name\RenameClassRector;
use RectorPrefix20210301\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210301\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(\Rector\Core\Configuration\Option::AUTO_IMPORT_NAMES, \true);
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class);
};
