<?php

declare (strict_types=1);
namespace RectorPrefix2020DecSat;

use Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector;
use Rector\CakePHP\ValueObject\ModalToGetSet;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use RectorPrefix2020DecSat\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
# source: https://book.cakephp.org/3.0/en/appendices/3-5-migration-guide.html
return static function (\RectorPrefix2020DecSat\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['RectorPrefix2020DecSat\\Cake\\Http\\Client\\CookieCollection' => 'RectorPrefix2020DecSat\\Cake\\Http\\Cookie\\CookieCollection', 'RectorPrefix2020DecSat\\Cake\\Console\\ShellDispatcher' => 'RectorPrefix2020DecSat\\Cake\\Console\\CommandRunner']]]);
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('RectorPrefix2020DecSat\\Cake\\Database\\Schema\\TableSchema', 'column', 'getColumn'), new \Rector\Renaming\ValueObject\MethodCallRename('RectorPrefix2020DecSat\\Cake\\Database\\Schema\\TableSchema', 'constraint', 'getConstraint'), new \Rector\Renaming\ValueObject\MethodCallRename('RectorPrefix2020DecSat\\Cake\\Database\\Schema\\TableSchema', 'index', 'getIndex')])]]);
    $services->set(\Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector::class)->call('configure', [[\Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector::UNPREFIXED_METHODS_TO_GET_SET => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Cache\\Cache', 'config'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Cache\\Cache', 'registry'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Console\\Shell', 'io'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Console\\ConsoleIo', 'outputAs'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Console\\ConsoleOutput', 'outputAs'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Database\\Connection', 'logger'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Database\\TypedResultInterface', 'returnType'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Database\\TypedResultTrait', 'returnType'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Database\\Log\\LoggingStatement', 'logger'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Datasource\\ModelAwareTrait', 'modelType'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Database\\Query', 'valueBinder', 'getValueBinder', 'valueBinder'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Database\\Schema\\TableSchema', 'columnType'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Datasource\\QueryTrait', 'eagerLoaded', 'isEagerLoaded', 'eagerLoaded'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Event\\EventDispatcherInterface', 'eventManager'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Event\\EventDispatcherTrait', 'eventManager'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Error\\Debugger', 'outputAs', 'getOutputFormat', 'setOutputFormat'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Http\\ServerRequest', 'env', 'getEnv', 'withEnv'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Http\\ServerRequest', 'charset', 'getCharset', 'withCharset'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\I18n\\I18n', 'locale'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\I18n\\I18n', 'translator'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\I18n\\I18n', 'defaultLocale'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\I18n\\I18n', 'defaultFormatter'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\ORM\\Association\\BelongsToMany', 'sort'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\ORM\\LocatorAwareTrait', 'tableLocator'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\ORM\\Table', 'validator'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Routing\\RouteBuilder', 'extensions'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Routing\\RouteBuilder', 'routeClass'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Routing\\RouteCollection', 'extensions'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\TestSuite\\TestFixture', 'schema'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\Utility\\Security', 'salt'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\View\\View', 'template'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\View\\View', 'layout'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\View\\View', 'theme'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\View\\View', 'templatePath'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\View\\View', 'layoutPath'), new \Rector\CakePHP\ValueObject\ModalToGetSet('RectorPrefix2020DecSat\\Cake\\View\\View', 'autoLayout', 'isAutoLayoutEnabled', 'enableAutoLayout')])]]);
};
