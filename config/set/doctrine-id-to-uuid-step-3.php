<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74;

use _PhpScoperb75b35f52b74\Rector\Doctrine\Rector\Class_\AddUuidMirrorForRelationPropertyRector;
use _PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\_PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    # add relations uuid properties
    $services->set(\_PhpScoperb75b35f52b74\Rector\Doctrine\Rector\Class_\AddUuidMirrorForRelationPropertyRector::class);
};
