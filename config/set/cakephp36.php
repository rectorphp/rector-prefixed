<?php

declare (strict_types=1);
namespace _PhpScoper50d83356d739;

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\Assign\PropertyToMethodRector;
use Rector\Transform\ValueObject\PropertyToMethod;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    # source: https://book.cakephp.org/3.0/en/appendices/3-6-migration-guide.html
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper50d83356d739\\Cake\\ORM\\Table', 'association', 'getAssociation'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper50d83356d739\\Cake\\Validation\\ValidationSet', 'isPresenceRequired', 'requirePresence'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper50d83356d739\\Cake\\Validation\\ValidationSet', 'isEmptyAllowed', 'allowEmpty')])]]);
    $services->set(\Rector\Transform\Rector\Assign\PropertyToMethodRector::class)->call('configure', [[\Rector\Transform\Rector\Assign\PropertyToMethodRector::PROPERTIES_TO_METHOD_CALLS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Transform\ValueObject\PropertyToMethod('_PhpScoper50d83356d739\\Cake\\Controller\\Controller', 'name', 'getName', 'setName'), new \Rector\Transform\ValueObject\PropertyToMethod('_PhpScoper50d83356d739\\Cake\\Controller\\Controller', 'plugin', 'getPlugin', 'setPlugin'), new \Rector\Transform\ValueObject\PropertyToMethod('_PhpScoper50d83356d739\\Cake\\Form\\Form', 'validator', 'getValidator', 'setValidator')])]]);
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoper50d83356d739\\Cake\\Cache\\Engine\\ApcEngine' => '_PhpScoper50d83356d739\\Cake\\Cache\\Engine\\ApcuEngine', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\BadRequestException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\BadRequestException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\ConflictException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\ConflictException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\ForbiddenException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\ForbiddenException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\GoneException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\GoneException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\HttpException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\HttpException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\InternalErrorException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\InternalErrorException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\InvalidCsrfTokenException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\InvalidCsrfTokenException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\MethodNotAllowedException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\MethodNotAllowedException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\NotAcceptableException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\NotAcceptableException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\NotFoundException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\NotFoundException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\NotImplementedException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\NotImplementedException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\ServiceUnavailableException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\ServiceUnavailableException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\UnauthorizedException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\UnauthorizedException', '_PhpScoper50d83356d739\\Cake\\Network\\Exception\\UnavailableForLegalReasonsException' => '_PhpScoper50d83356d739\\Cake\\Http\\Exception\\UnavailableForLegalReasonsException', '_PhpScoper50d83356d739\\Cake\\Network\\Session' => '_PhpScoper50d83356d739\\Cake\\Http\\Session', '_PhpScoper50d83356d739\\Cake\\Network\\Session\\DatabaseSession' => '_PhpScoper50d83356d739\\Cake\\Http\\Session\\DatabaseSession', '_PhpScoper50d83356d739\\Cake\\Network\\Session\\CacheSession' => '_PhpScoper50d83356d739\\Cake\\Http\\Session\\CacheSession', '_PhpScoper50d83356d739\\Cake\\Network\\CorsBuilder' => '_PhpScoper50d83356d739\\Cake\\Http\\CorsBuilder', '_PhpScoper50d83356d739\\Cake\\View\\Widget\\WidgetRegistry' => '_PhpScoper50d83356d739\\Cake\\View\\Widget\\WidgetLocator']]]);
};
