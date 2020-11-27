<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use Rector\CodeQuality\Rector\If_\MoveOutMethodCallInsideIfConditionRector;
use Rector\Performance\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\CodeQuality\Rector\If_\MoveOutMethodCallInsideIfConditionRector::class);
    $services->set(\Rector\Performance\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector::class);
};