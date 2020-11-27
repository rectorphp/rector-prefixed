<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoperbd5d0c5f7638\\Kdyby\\Translation\\Translator' => '_PhpScoperbd5d0c5f7638\\Nette\\Localization\\ITranslator', '_PhpScoperbd5d0c5f7638\\Kdyby\\Translation\\DI\\ITranslationProvider' => '_PhpScoperbd5d0c5f7638\\Contributte\\Translation\\DI\\TranslationProviderInterface', '_PhpScoperbd5d0c5f7638\\Kdyby\\Translation\\Phrase' => '_PhpScoperbd5d0c5f7638\\Contributte\\Translation\\Wrappers\\Message']]]);
};