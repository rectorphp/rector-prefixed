<?php

declare (strict_types=1);
namespace _PhpScoper5b8c9e9ebd21;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use _PhpScoper5b8c9e9ebd21\Symfony\Component\Yaml\Parser;
use Symplify\PhpConfigPrinter\Printer\PhpParserPhpConfigPrinter;
use Symplify\PhpConfigPrinter\YamlToPhpConverter;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Rector\\SymfonyPhpConfig\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Rector']);
    $services->set(\Symplify\PhpConfigPrinter\YamlToPhpConverter::class);
    $services->set(\_PhpScoper5b8c9e9ebd21\Symfony\Component\Yaml\Parser::class);
    $services->set(\Symplify\PhpConfigPrinter\Printer\PhpParserPhpConfigPrinter::class);
};
