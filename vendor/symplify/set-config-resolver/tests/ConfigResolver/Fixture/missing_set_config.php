<?php

declare (strict_types=1);
namespace RectorPrefix20210203;

use RectorPrefix20210203\PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use RectorPrefix20210203\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210203\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set('sets', ['not_here']);
    $services = $containerConfigurator->services();
    $services->set(\RectorPrefix20210203\PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer::class)->call('configure', [['syntax' => 'short']]);
};
