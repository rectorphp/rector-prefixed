<?php

declare (strict_types=1);
namespace _PhpScoper267b3276efc2;

use Rector\Generic\Rector\String_\StringToClassConstantRector;
use Rector\Generic\ValueObject\StringToClassConstant;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
# see: https://laravel.com/docs/5.2/upgrade
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoper267b3276efc2\\Illuminate\\Auth\\Access\\UnauthorizedException' => '_PhpScoper267b3276efc2\\Illuminate\\Auth\\Access\\AuthorizationException', '_PhpScoper267b3276efc2\\Illuminate\\Http\\Exception\\HttpResponseException' => '_PhpScoper267b3276efc2\\Illuminate\\Foundation\\Validation\\ValidationException', '_PhpScoper267b3276efc2\\Illuminate\\Foundation\\Composer' => '_PhpScoper267b3276efc2\\Illuminate\\Support\\Composer']]]);
    $services->set(\Rector\Generic\Rector\String_\StringToClassConstantRector::class)->call('configure', [[\Rector\Generic\Rector\String_\StringToClassConstantRector::STRINGS_TO_CLASS_CONSTANTS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\StringToClassConstant('artisan.start', '_PhpScoper267b3276efc2\\Illuminate\\Console\\Events\\ArtisanStarting', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('auth.attempting', '_PhpScoper267b3276efc2\\Illuminate\\Auth\\Events\\Attempting', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('auth.login', '_PhpScoper267b3276efc2\\Illuminate\\Auth\\Events\\Login', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('auth.logout', '_PhpScoper267b3276efc2\\Illuminate\\Auth\\Events\\Logout', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.missed', '_PhpScoper267b3276efc2\\Illuminate\\Cache\\Events\\CacheMissed', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.hit', '_PhpScoper267b3276efc2\\Illuminate\\Cache\\Events\\CacheHit', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.write', '_PhpScoper267b3276efc2\\Illuminate\\Cache\\Events\\KeyWritten', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.delete', '_PhpScoper267b3276efc2\\Illuminate\\Cache\\Events\\KeyForgotten', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.query', '_PhpScoper267b3276efc2\\Illuminate\\Database\\Events\\QueryExecuted', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.before', '_PhpScoper267b3276efc2\\Illuminate\\Queue\\Events\\JobProcessing', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.after', '_PhpScoper267b3276efc2\\Illuminate\\Queue\\Events\\JobProcessed', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.failed', '_PhpScoper267b3276efc2\\Illuminate\\Queue\\Events\\JobFailed', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.stopping', '_PhpScoper267b3276efc2\\Illuminate\\Queue\\Events\\WorkerStopping', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('mailer.sending', '_PhpScoper267b3276efc2\\Illuminate\\Mail\\Events\\MessageSending', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('router.matched', '_PhpScoper267b3276efc2\\Illuminate\\Routing\\Events\\RouteMatched', 'class')])]]);
};
