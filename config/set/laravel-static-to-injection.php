<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use Rector\Generic\Rector\FuncCall\FuncCallToNewRector;
use Rector\Laravel\Rector\FuncCall\HelperFuncCallToFacadeClassRector;
use Rector\Laravel\Rector\StaticCall\RequestStaticValidateToInjectRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Transform\Rector\FuncCall\ArgumentFuncCallToMethodCallRector;
use Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector;
use Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall;
use Rector\Transform\ValueObject\ArrayFuncCallToMethodCall;
use Rector\Transform\ValueObject\StaticCallToMethodCall;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
/**
 * @see https://www.freecodecamp.org/news/moving-away-from-magic-or-why-i-dont-want-to-use-laravel-anymore-2ce098c979bd/
 * @see https://tomasvotruba.com/blog/2019/03/04/how-to-turn-laravel-from-static-to-dependency-injection-in-one-day/
 * @see https://laravel.com/docs/5.7/facades#facades-vs-dependency-injection
 */
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $containerConfigurator->import(__DIR__ . '/laravel-array-str-functions-to-static-call.php');
    $services = $containerConfigurator->services();
    $services->set(\Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector::class)->call('configure', [[\Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector::STATIC_CALLS_TO_METHOD_CALLS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\App', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Foundation\\Application', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Artisan', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Console\\Kernel', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Auth', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Auth\\AuthManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Blade', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\View\\Compilers\\BladeCompiler', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Broadcast', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Broadcasting\\Factory', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Bus', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Bus\\Dispatcher', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Cache', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Cache\\CacheManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Config', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Config\\Repository', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Cookie', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Cookie\\CookieJar', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Crypt', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Encryption\\Encrypter', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\DB', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Database\\DatabaseManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Event', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Events\\Dispatcher', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\File', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Filesystem\\Filesystem', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Gate', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Auth\\Access\\Gate', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Hash', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Hashing\\Hasher', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Lang', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Translation\\Translator', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Log', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Log\\LogManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Mail', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Mail\\Mailer', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Notification', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Notifications\\ChannelManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Password', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Auth\\Passwords\\PasswordBrokerManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Queue', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Queue\\QueueManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Redirect', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\Redirector', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Redis', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Redis\\RedisManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Request', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Http\\Request', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Response', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Routing\\ResponseFactory', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Route', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\Router', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Schema', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Database\\Schema\\Builder', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Session', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Session\\SessionManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Storage', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Filesystem\\FilesystemManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\URL', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\UrlGenerator', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Validator', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\Validation\\Factory', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\View', '*', '_PhpScoperbd5d0c5f7638\\Illuminate\\View\\Factory', '*')])]]);
    $services->set(\Rector\Laravel\Rector\StaticCall\RequestStaticValidateToInjectRector::class);
    // @see https://github.com/laravel/framework/blob/78828bc779e410e03cc6465f002b834eadf160d2/src/Illuminate/Foundation/helpers.php#L959
    // @see https://gist.github.com/barryvdh/bb6ffc5d11e0a75dba67
    $services->set(\Rector\Transform\Rector\FuncCall\ArgumentFuncCallToMethodCallRector::class)->call('configure', [[\Rector\Transform\Rector\FuncCall\ArgumentFuncCallToMethodCallRector::FUNCTIONS_TO_METHOD_CALLS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('auth', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Auth\\Guard'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('policy', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Auth\\Access\\Gate', 'getPolicyFor'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('cookie', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Cookie\\Factory', 'make'),
        // router
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('put', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\Router', 'put'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('get', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\Router', 'get'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('post', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\Router', 'post'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('patch', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\Router', 'patch'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('delete', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\Router', 'delete'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('resource', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\Router', 'resource'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('response', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Routing\\ResponseFactory', 'make'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('info', '_PhpScoperbd5d0c5f7638\\Illuminate\\Log\\Writer', 'info'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('view', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\View\\Factory', 'make'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('bcrypt', '_PhpScoperbd5d0c5f7638\\Illuminate\\Hashing\\BcryptHasher', 'make'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('redirect', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\Redirector', 'back'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('broadcast', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Broadcasting\\Factory', 'event'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('event', '_PhpScoperbd5d0c5f7638\\Illuminate\\Events\\Dispatcher', 'dispatch'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('dispatch', '_PhpScoperbd5d0c5f7638\\Illuminate\\Events\\Dispatcher', 'dispatch'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('route', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\UrlGenerator', 'route'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('asset', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\UrlGenerator', 'asset'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('url', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Routing\\UrlGenerator', 'to'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('action', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\UrlGenerator', 'action'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('trans', '_PhpScoperbd5d0c5f7638\\Illuminate\\Translation\\Translator', 'trans'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('trans_choice', '_PhpScoperbd5d0c5f7638\\Illuminate\\Translation\\Translator', 'transChoice'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('logger', '_PhpScoperbd5d0c5f7638\\Illuminate\\Log\\Writer', 'debug'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('back', '_PhpScoperbd5d0c5f7638\\Illuminate\\Routing\\Redirector', 'back', 'back'),
    ]), \Rector\Transform\Rector\FuncCall\ArgumentFuncCallToMethodCallRector::ARRAY_FUNCTIONS_TO_METHOD_CALLS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Transform\ValueObject\ArrayFuncCallToMethodCall('config', '_PhpScoperbd5d0c5f7638\\Illuminate\\Contracts\\Config\\Repository', 'set', 'get'), new \Rector\Transform\ValueObject\ArrayFuncCallToMethodCall('session', '_PhpScoperbd5d0c5f7638\\Illuminate\\Session\\SessionManager', 'put', 'get')])]]);
    $services->set(\Rector\Generic\Rector\FuncCall\FuncCallToNewRector::class)->call('configure', [[\Rector\Generic\Rector\FuncCall\FuncCallToNewRector::FUNCTION_TO_NEW => ['collect' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Collection']]]);
    $services->set(\Rector\Laravel\Rector\FuncCall\HelperFuncCallToFacadeClassRector::class);
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['App' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\App', 'Artisan' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Artisan', 'Auth' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Auth', 'Blade' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Blade', 'Broadcast' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Broadcast', 'Bus' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Bus', 'Cache' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Cache', 'Config' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Config', 'Cookie' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Cookie', 'Crypt' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Crypt', 'DB' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\DB', 'Date' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Date', 'Event' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Event', 'Facade' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Facade', 'File' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\File', 'Gate' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Gate', 'Hash' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Hash', 'Http' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Http', 'Lang' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Lang', 'Log' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Log', 'Mail' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Mail', 'Notification' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Notification', 'Password' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Password', 'Queue' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Queue', 'RateLimiter' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\RateLimiter', 'Redirect' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Redirect', 'Redis' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Redis', 'Request' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Http\\Request', 'Response' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Response', 'Route' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Route', 'Schema' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Schema', 'Session' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Session', 'Storage' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Storage', 'URL' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\URL', 'Validator' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\Validator', 'View' => '_PhpScoperbd5d0c5f7638\\Illuminate\\Support\\Facades\\View']]]);
};
