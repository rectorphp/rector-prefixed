<?php

namespace RectorPrefix20210215;

use Rector\Php74\Rector\Property\TypedPropertyRector;
use RectorPrefix20210215\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210215\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Php74\Rector\Property\TypedPropertyRector::class)->call('configure', [[\Rector\Php74\Rector\Property\TypedPropertyRector::CLASS_LIKE_TYPE_ONLY => \true]]);
};
