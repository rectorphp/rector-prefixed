<?php

declare (strict_types=1);
namespace _PhpScoperfce0de0de1ce;

use Rector\Generic\Rector\FuncCall\FuncCallToNewRector;
use Rector\Laravel\Rector\FuncCall\HelperFuncCallToFacadeClassRector;
use Rector\Laravel\Rector\StaticCall\RequestStaticValidateToInjectRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Transform\Rector\FuncCall\ArgumentFuncCallToMethodCallRector;
use Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector;
use Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall;
use Rector\Transform\ValueObject\ArrayFuncCallToMethodCall;
use Rector\Transform\ValueObject\StaticCallToMethodCall;
use _PhpScoperfce0de0de1ce\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
/**
 * @see https://www.freecodecamp.org/news/moving-away-from-magic-or-why-i-dont-want-to-use-laravel-anymore-2ce098c979bd/
 * @see https://tomasvotruba.com/blog/2019/03/04/how-to-turn-laravel-from-static-to-dependency-injection-in-one-day/
 * @see https://laravel.com/docs/5.7/facades#facades-vs-dependency-injection
 */
return static function (\_PhpScoperfce0de0de1ce\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $containerConfigurator->import(__DIR__ . '/laravel-array-str-functions-to-static-call.php');
    $services = $containerConfigurator->services();
    $services->set(\Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector::class)->call('configure', [[\Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector::STATIC_CALLS_TO_METHOD_CALLS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\App', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Foundation\\Application', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Artisan', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Console\\Kernel', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Auth', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Auth\\AuthManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Blade', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\View\\Compilers\\BladeCompiler', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Broadcast', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Broadcasting\\Factory', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Bus', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Bus\\Dispatcher', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Cache', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Cache\\CacheManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Config', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Config\\Repository', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Cookie', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Cookie\\CookieJar', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Crypt', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Encryption\\Encrypter', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\DB', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Database\\DatabaseManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Event', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Events\\Dispatcher', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\File', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Filesystem\\Filesystem', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Gate', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Auth\\Access\\Gate', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Hash', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Hashing\\Hasher', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Lang', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Translation\\Translator', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Log', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Log\\LogManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Mail', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Mail\\Mailer', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Notification', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Notifications\\ChannelManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Password', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Auth\\Passwords\\PasswordBrokerManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Queue', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Queue\\QueueManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Redirect', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\Redirector', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Redis', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Redis\\RedisManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Request', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Http\\Request', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Response', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Routing\\ResponseFactory', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Route', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\Router', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Schema', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Database\\Schema\\Builder', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Session', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Session\\SessionManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Storage', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Filesystem\\FilesystemManager', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\URL', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\UrlGenerator', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Validator', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\Validation\\Factory', '*'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\View', '*', '_PhpScoperfce0de0de1ce\\Illuminate\\View\\Factory', '*')])]]);
    $services->set(\Rector\Laravel\Rector\StaticCall\RequestStaticValidateToInjectRector::class);
    // @see https://github.com/laravel/framework/blob/78828bc779e410e03cc6465f002b834eadf160d2/src/Illuminate/Foundation/helpers.php#L959
    // @see https://gist.github.com/barryvdh/bb6ffc5d11e0a75dba67
    $services->set(\Rector\Transform\Rector\FuncCall\ArgumentFuncCallToMethodCallRector::class)->call('configure', [[\Rector\Transform\Rector\FuncCall\ArgumentFuncCallToMethodCallRector::FUNCTIONS_TO_METHOD_CALLS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('auth', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Auth\\Guard'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('policy', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Auth\\Access\\Gate', 'getPolicyFor'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('cookie', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Cookie\\Factory', 'make'),
        // router
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('put', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\Router', 'put'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('get', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\Router', 'get'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('post', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\Router', 'post'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('patch', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\Router', 'patch'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('delete', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\Router', 'delete'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('resource', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\Router', 'resource'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('response', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Routing\\ResponseFactory', 'make'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('info', '_PhpScoperfce0de0de1ce\\Illuminate\\Log\\Writer', 'info'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('view', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\View\\Factory', 'make'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('bcrypt', '_PhpScoperfce0de0de1ce\\Illuminate\\Hashing\\BcryptHasher', 'make'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('redirect', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\Redirector', 'back'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('broadcast', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Broadcasting\\Factory', 'event'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('event', '_PhpScoperfce0de0de1ce\\Illuminate\\Events\\Dispatcher', 'dispatch'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('dispatch', '_PhpScoperfce0de0de1ce\\Illuminate\\Events\\Dispatcher', 'dispatch'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('route', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\UrlGenerator', 'route'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('asset', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\UrlGenerator', 'asset'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('url', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Routing\\UrlGenerator', 'to'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('action', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\UrlGenerator', 'action'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('trans', '_PhpScoperfce0de0de1ce\\Illuminate\\Translation\\Translator', 'trans'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('trans_choice', '_PhpScoperfce0de0de1ce\\Illuminate\\Translation\\Translator', 'transChoice'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('logger', '_PhpScoperfce0de0de1ce\\Illuminate\\Log\\Writer', 'debug'),
        new \Rector\Transform\ValueObject\ArgumentFuncCallToMethodCall('back', '_PhpScoperfce0de0de1ce\\Illuminate\\Routing\\Redirector', 'back', 'back'),
    ]), \Rector\Transform\Rector\FuncCall\ArgumentFuncCallToMethodCallRector::ARRAY_FUNCTIONS_TO_METHOD_CALLS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Transform\ValueObject\ArrayFuncCallToMethodCall('config', '_PhpScoperfce0de0de1ce\\Illuminate\\Contracts\\Config\\Repository', 'set', 'get'), new \Rector\Transform\ValueObject\ArrayFuncCallToMethodCall('session', '_PhpScoperfce0de0de1ce\\Illuminate\\Session\\SessionManager', 'put', 'get')])]]);
    $services->set(\Rector\Generic\Rector\FuncCall\FuncCallToNewRector::class)->call('configure', [[\Rector\Generic\Rector\FuncCall\FuncCallToNewRector::FUNCTION_TO_NEW => ['collect' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Collection']]]);
    $services->set(\Rector\Laravel\Rector\FuncCall\HelperFuncCallToFacadeClassRector::class);
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['App' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\App', 'Artisan' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Artisan', 'Auth' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Auth', 'Blade' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Blade', 'Broadcast' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Broadcast', 'Bus' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Bus', 'Cache' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Cache', 'Config' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Config', 'Cookie' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Cookie', 'Crypt' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Crypt', 'DB' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\DB', 'Date' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Date', 'Event' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Event', 'Facade' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Facade', 'File' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\File', 'Gate' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Gate', 'Hash' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Hash', 'Http' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Http', 'Lang' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Lang', 'Log' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Log', 'Mail' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Mail', 'Notification' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Notification', 'Password' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Password', 'Queue' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Queue', 'RateLimiter' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\RateLimiter', 'Redirect' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Redirect', 'Redis' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Redis', 'Request' => '_PhpScoperfce0de0de1ce\\Illuminate\\Http\\Request', 'Response' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Response', 'Route' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Route', 'Schema' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Schema', 'Session' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Session', 'Storage' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Storage', 'URL' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\URL', 'Validator' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\Validator', 'View' => '_PhpScoperfce0de0de1ce\\Illuminate\\Support\\Facades\\View']]]);
};
