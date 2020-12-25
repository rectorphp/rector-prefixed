<?php

declare (strict_types=1);
namespace _PhpScoper17db12703726;

use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['CI_Controller' => '_PhpScoper17db12703726\\CodeIgniter\\Controller', 'CI_Model' => '_PhpScoper17db12703726\\CodeIgniter\\Model']]]);
};
