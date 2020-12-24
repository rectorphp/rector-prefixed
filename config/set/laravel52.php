<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74;

use _PhpScoperb75b35f52b74\Rector\Generic\Rector\String_\StringToClassConstantRector;
use _PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant;
use _PhpScoperb75b35f52b74\Rector\Renaming\Rector\Name\RenameClassRector;
use _PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use _PhpScoperb75b35f52b74\Symplify\SymfonyPhpConfig\ValueObjectInliner;
# see: https://laravel.com/docs/5.2/upgrade
return static function (\_PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScoperb75b35f52b74\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\_PhpScoperb75b35f52b74\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoperb75b35f52b74\\Illuminate\\Auth\\Access\\UnauthorizedException' => '_PhpScoperb75b35f52b74\\Illuminate\\Auth\\Access\\AuthorizationException', '_PhpScoperb75b35f52b74\\Illuminate\\Http\\Exception\\HttpResponseException' => '_PhpScoperb75b35f52b74\\Illuminate\\Foundation\\Validation\\ValidationException', '_PhpScoperb75b35f52b74\\Illuminate\\Foundation\\Composer' => '_PhpScoperb75b35f52b74\\Illuminate\\Support\\Composer']]]);
    $services->set(\_PhpScoperb75b35f52b74\Rector\Generic\Rector\String_\StringToClassConstantRector::class)->call('configure', [[\_PhpScoperb75b35f52b74\Rector\Generic\Rector\String_\StringToClassConstantRector::STRINGS_TO_CLASS_CONSTANTS => \_PhpScoperb75b35f52b74\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('artisan.start', '_PhpScoperb75b35f52b74\\Illuminate\\Console\\Events\\ArtisanStarting', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('auth.attempting', '_PhpScoperb75b35f52b74\\Illuminate\\Auth\\Events\\Attempting', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('auth.login', '_PhpScoperb75b35f52b74\\Illuminate\\Auth\\Events\\Login', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('auth.logout', '_PhpScoperb75b35f52b74\\Illuminate\\Auth\\Events\\Logout', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('cache.missed', '_PhpScoperb75b35f52b74\\Illuminate\\Cache\\Events\\CacheMissed', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('cache.hit', '_PhpScoperb75b35f52b74\\Illuminate\\Cache\\Events\\CacheHit', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('cache.write', '_PhpScoperb75b35f52b74\\Illuminate\\Cache\\Events\\KeyWritten', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('cache.delete', '_PhpScoperb75b35f52b74\\Illuminate\\Cache\\Events\\KeyForgotten', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('illuminate.query', '_PhpScoperb75b35f52b74\\Illuminate\\Database\\Events\\QueryExecuted', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.before', '_PhpScoperb75b35f52b74\\Illuminate\\Queue\\Events\\JobProcessing', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.after', '_PhpScoperb75b35f52b74\\Illuminate\\Queue\\Events\\JobProcessed', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.failed', '_PhpScoperb75b35f52b74\\Illuminate\\Queue\\Events\\JobFailed', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.stopping', '_PhpScoperb75b35f52b74\\Illuminate\\Queue\\Events\\WorkerStopping', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('mailer.sending', '_PhpScoperb75b35f52b74\\Illuminate\\Mail\\Events\\MessageSending', 'class'), new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\StringToClassConstant('router.matched', '_PhpScoperb75b35f52b74\\Illuminate\\Routing\\Events\\RouteMatched', 'class')])]]);
};
