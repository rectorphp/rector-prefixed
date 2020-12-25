<?php

declare (strict_types=1);
namespace _PhpScoper267b3276efc2;

use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoper267b3276efc2\\Doctrine\\Common\\DataFixtures\\AbstractFixture' => '_PhpScoper267b3276efc2\\Doctrine\\Bundle\\FixturesBundle\\Fixture']]]);
};
