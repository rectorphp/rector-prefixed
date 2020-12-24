<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72;

use _PhpScopere8e811afab72\Rector\Generic\Rector\String_\StringToClassConstantRector;
use _PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant;
use _PhpScopere8e811afab72\Rector\Renaming\Rector\Name\RenameClassRector;
use _PhpScopere8e811afab72\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use _PhpScopere8e811afab72\Symplify\SymfonyPhpConfig\ValueObjectInliner;
# see: https://laravel.com/docs/5.2/upgrade
return static function (\_PhpScopere8e811afab72\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScopere8e811afab72\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\_PhpScopere8e811afab72\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScopere8e811afab72\\Illuminate\\Auth\\Access\\UnauthorizedException' => '_PhpScopere8e811afab72\\Illuminate\\Auth\\Access\\AuthorizationException', '_PhpScopere8e811afab72\\Illuminate\\Http\\Exception\\HttpResponseException' => '_PhpScopere8e811afab72\\Illuminate\\Foundation\\Validation\\ValidationException', '_PhpScopere8e811afab72\\Illuminate\\Foundation\\Composer' => '_PhpScopere8e811afab72\\Illuminate\\Support\\Composer']]]);
    $services->set(\_PhpScopere8e811afab72\Rector\Generic\Rector\String_\StringToClassConstantRector::class)->call('configure', [[\_PhpScopere8e811afab72\Rector\Generic\Rector\String_\StringToClassConstantRector::STRINGS_TO_CLASS_CONSTANTS => \_PhpScopere8e811afab72\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('artisan.start', '_PhpScopere8e811afab72\\Illuminate\\Console\\Events\\ArtisanStarting', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('auth.attempting', '_PhpScopere8e811afab72\\Illuminate\\Auth\\Events\\Attempting', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('auth.login', '_PhpScopere8e811afab72\\Illuminate\\Auth\\Events\\Login', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('auth.logout', '_PhpScopere8e811afab72\\Illuminate\\Auth\\Events\\Logout', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('cache.missed', '_PhpScopere8e811afab72\\Illuminate\\Cache\\Events\\CacheMissed', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('cache.hit', '_PhpScopere8e811afab72\\Illuminate\\Cache\\Events\\CacheHit', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('cache.write', '_PhpScopere8e811afab72\\Illuminate\\Cache\\Events\\KeyWritten', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('cache.delete', '_PhpScopere8e811afab72\\Illuminate\\Cache\\Events\\KeyForgotten', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('illuminate.query', '_PhpScopere8e811afab72\\Illuminate\\Database\\Events\\QueryExecuted', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.before', '_PhpScopere8e811afab72\\Illuminate\\Queue\\Events\\JobProcessing', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.after', '_PhpScopere8e811afab72\\Illuminate\\Queue\\Events\\JobProcessed', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.failed', '_PhpScopere8e811afab72\\Illuminate\\Queue\\Events\\JobFailed', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.stopping', '_PhpScopere8e811afab72\\Illuminate\\Queue\\Events\\WorkerStopping', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('mailer.sending', '_PhpScopere8e811afab72\\Illuminate\\Mail\\Events\\MessageSending', 'class'), new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\StringToClassConstant('router.matched', '_PhpScopere8e811afab72\\Illuminate\\Routing\\Events\\RouteMatched', 'class')])]]);
};
