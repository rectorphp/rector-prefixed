<?php

declare (strict_types=1);
namespace RectorPrefix20201228;

use Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector;
use Rector\CakePHP\Rector\Property\ChangeSnakedFixtureNameToPascalRector;
use Rector\CakePHP\ValueObject\ModalToGetSet;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\Assign\PropertyToMethodRector;
use Rector\Transform\Rector\MethodCall\MethodCallToAnotherMethodCallWithArgumentsRector;
use Rector\Transform\ValueObject\MethodCallToAnotherMethodCallWithArguments;
use Rector\Transform\ValueObject\PropertyToMethod;
use RectorPrefix20201228\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner;
# source: https://book.cakephp.org/3.0/en/appendices/3-7-migration-guide.html
return static function (\RectorPrefix20201228\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Form\\Form', 'errors', 'getErrors'), new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Validation\\Validation', 'cc', 'creditCard'), new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Filesystem\\Folder', 'normalizePath', 'correctSlashFor'), new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Http\\Client\\Response', 'body', 'getStringBody'), new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Core\\Plugin', 'unload', 'clear')])]]);
    $services->set(\Rector\Transform\Rector\Assign\PropertyToMethodRector::class)->call('configure', [[\Rector\Transform\Rector\Assign\PropertyToMethodRector::PROPERTIES_TO_METHOD_CALLS => \RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\Http\\Client\\Response', 'body', 'getStringBody'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\Http\\Client\\Response', 'json', 'getJson'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\Http\\Client\\Response', 'xml', 'getXml'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\Http\\Client\\Response', 'cookies', 'getCookies'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\Http\\Client\\Response', 'code', 'getStatusCode'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'request', 'getRequest', 'setRequest'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'response', 'getResponse', 'setResponse'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'templatePath', 'getTemplatePath', 'setTemplatePath'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'template', 'getTemplate', 'setTemplate'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'layout', 'getLayout', 'setLayout'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'layoutPath', 'getLayoutPath', 'setLayoutPath'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'autoLayout', 'isAutoLayoutEnabled', 'enableAutoLayout'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'theme', 'getTheme', 'setTheme'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'subDir', 'getSubDir', 'setSubDir'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'plugin', 'getPlugin', 'setPlugin'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'name', 'getName', 'setName'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'elementCache', 'getElementCache', 'setElementCache'), new \Rector\Transform\ValueObject\PropertyToMethod('Cake\\View\\View', 'helpers', 'helpers')])]]);
    $services->set(\Rector\Transform\Rector\MethodCall\MethodCallToAnotherMethodCallWithArgumentsRector::class)->call('configure', [[\Rector\Transform\Rector\MethodCall\MethodCallToAnotherMethodCallWithArgumentsRector::METHOD_CALL_RENAMES_WITH_ADDED_ARGUMENTS => \RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Transform\ValueObject\MethodCallToAnotherMethodCallWithArguments('Cake\\Database\\Query', 'join', 'clause', ['join']), new \Rector\Transform\ValueObject\MethodCallToAnotherMethodCallWithArguments('Cake\\Database\\Query', 'from', 'clause', ['from'])])]]);
    $services->set(\Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector::class)->call('configure', [[\Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector::UNPREFIXED_METHODS_TO_GET_SET => \RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\CakePHP\ValueObject\ModalToGetSet('Cake\\Database\\Connection', 'logQueries', 'isQueryLoggingEnabled', 'enableQueryLogging'), new \Rector\CakePHP\ValueObject\ModalToGetSet('Cake\\ORM\\Association', 'className', 'getClassName', 'setClassName')])]]);
    $services->set(\Rector\CakePHP\Rector\Property\ChangeSnakedFixtureNameToPascalRector::class);
};
