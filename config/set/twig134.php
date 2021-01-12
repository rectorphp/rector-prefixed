<?php

declare (strict_types=1);
namespace RectorPrefix20210112;

use Rector\Twig\Rector\Return_\SimpleFunctionAndFilterRector;
use RectorPrefix20210112\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210112\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Twig\Rector\Return_\SimpleFunctionAndFilterRector::class);
};
