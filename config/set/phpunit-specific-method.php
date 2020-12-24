<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74;

use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\Foreach_\SimplifyForeachInstanceOfRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertCompareToSpecificMethodRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertComparisonToSpecificMethodRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertFalseStrposToContainsRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertInstanceOfComparisonRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertIssetToSpecificMethodRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertNotOperatorRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertPropertyExistsRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertRegExpRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertSameBoolNullToSpecificMethodRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertTrueFalseInternalTypeToSpecificMethodRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertTrueFalseToSpecificMethodRector;
use _PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\_PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertNotOperatorRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertComparisonToSpecificMethodRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertTrueFalseToSpecificMethodRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertSameBoolNullToSpecificMethodRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertFalseStrposToContainsRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertTrueFalseInternalTypeToSpecificMethodRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertCompareToSpecificMethodRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertIssetToSpecificMethodRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertInstanceOfComparisonRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertPropertyExistsRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall\AssertRegExpRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\Foreach_\SimplifyForeachInstanceOfRector::class);
};
