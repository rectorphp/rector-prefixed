<?php

declare (strict_types=1);
namespace RectorPrefix20210122;

use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector;
use Rector\CakePHP\Rector\MethodCall\RenameMethodCallBasedOnParameterRector;
use Rector\CakePHP\ValueObject\ModalToGetSet;
use Rector\CakePHP\ValueObject\RenameMethodCallBasedOnParameter;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstantRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassConstant;
use Rector\Renaming\ValueObject\RenameProperty;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use RectorPrefix20210122\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
# source: https://book.cakephp.org/4/en/appendices/4-0-migration-guide.html
return static function (\RectorPrefix20210122\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['Cake\\Database\\Type' => 'Cake\\Database\\TypeFactory', 'Cake\\Console\\ConsoleErrorHandler' => 'Cake\\Error\\ConsoleErrorHandler']]]);
    $services->set(\Rector\Renaming\Rector\ClassConstFetch\RenameClassConstantRector::class)->call('configure', [[\Rector\Renaming\Rector\ClassConstFetch\RenameClassConstantRector::CLASS_CONSTANT_RENAME => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\RenameClassConstant('Cake\\View\\View', 'NAME_ELEMENT', 'TYPE_ELEMENT'), new \Rector\Renaming\ValueObject\RenameClassConstant('Cake\\View\\View', 'NAME_LAYOUT', 'TYPE_LAYOUT'), new \Rector\Renaming\ValueObject\RenameClassConstant('Cake\\Mailer\\Email', 'MESSAGE_HTML', 'Cake\\Mailer\\Message::MESSAGE_HTML'), new \Rector\Renaming\ValueObject\RenameClassConstant('Cake\\Mailer\\Email', 'MESSAGE_TEXT', 'Cake\\Mailer\\Message::MESSAGE_TEXT'), new \Rector\Renaming\ValueObject\RenameClassConstant('Cake\\Mailer\\Email', 'MESSAGE_BOTH', 'Cake\\Mailer\\Message::MESSAGE_BOTH'), new \Rector\Renaming\ValueObject\RenameClassConstant('Cake\\Mailer\\Email', 'EMAIL_PATTERN', 'Cake\\Mailer\\Message::EMAIL_PATTERN')])]]);
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Form\\Form', 'errors', 'getErrors'), new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Mailer\\Email', 'set', 'setViewVars'), new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\ORM\\EntityInterface', 'unsetProperty', 'unset'), new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Cache\\Cache', 'engine', 'pool'), new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Http\\Cookie\\Cookie', 'getStringValue', 'getScalarValue'), new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Validation\\Validator', 'containsNonAlphaNumeric', 'notAlphaNumeric'), new \Rector\Renaming\ValueObject\MethodCallRename('Cake\\Validation\\Validator', 'errors', 'validate')])]]);
    $services->set(\Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\RenameStaticMethod('Router', 'pushRequest', 'Router', 'setRequest'), new \Rector\Renaming\ValueObject\RenameStaticMethod('Router', 'setRequestInfo', 'Router', 'setRequest'), new \Rector\Renaming\ValueObject\RenameStaticMethod('Router', 'setRequestContext', 'Router', 'setRequest')])]]);
    $services->set(\Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector::class)->call('configure', [[\Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector::RENAMED_PROPERTIES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\RenameProperty('Cake\\ORM\\Entity', '_properties', '_fields')])]]);
    $services->set(\Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector::class)->call('configure', [[\Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector::METHOD_RETURN_TYPES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\Http\\BaseApplication', 'bootstrap', new \PHPStan\Type\VoidType()), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\Http\\BaseApplication', 'bootstrapCli', new \PHPStan\Type\VoidType()), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\Http\\BaseApplication', 'middleware', new \PHPStan\Type\ObjectType('Cake\\Http\\MiddlewareQueue')), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\Console\\Shell', 'initialize', new \PHPStan\Type\VoidType()), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\Controller\\Component', 'initialize', new \PHPStan\Type\VoidType()), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\Controller\\Controller', 'initialize', new \PHPStan\Type\VoidType()), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\Controller\\Controller', 'render', new \PHPStan\Type\ObjectType('Cake\\Http\\Response')), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\Form\\Form', 'validate', new \PHPStan\Type\BooleanType()), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\Form\\Form', '_buildSchema', new \PHPStan\Type\ObjectType('Cake\\Form\\Schema')), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\ORM\\Behavior', 'initialize', new \PHPStan\Type\VoidType()), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\ORM\\Table', 'initialize', new \PHPStan\Type\VoidType()), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\ORM\\Table', 'updateAll', new \PHPStan\Type\IntegerType()), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\ORM\\Table', 'deleteAll', new \PHPStan\Type\IntegerType()), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\ORM\\Table', 'validationDefault', new \PHPStan\Type\ObjectType('Cake\\Validation\\Validator')), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\ORM\\Table', 'buildRules', new \PHPStan\Type\ObjectType('Cake\\ORM\\RulesChecker')), new \Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('Cake\\View\\Helper', 'initialize', new \PHPStan\Type\VoidType())])]]);
    $eventInterfaceObjectType = new \PHPStan\Type\ObjectType('Cake\\Event\\EventInterface');
    $services->set(\Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector::class)->call('configure', [[\Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector::PARAMETER_TYPEHINTS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\Form\\Form', 'getData', 0, new \PHPStan\Type\UnionType([new \PHPStan\Type\StringType(), new \PHPStan\Type\NullType()])), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Behavior', 'beforeFind', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Behavior', 'buildValidator', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Behavior', 'buildRules', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Behavior', 'beforeRules', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Behavior', 'afterRules', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Behavior', 'beforeSave', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Behavior', 'afterSave', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Behavior', 'beforeDelete', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Behavior', 'afterDelete', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Table', 'beforeFind', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Table', 'buildValidator', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Table', 'buildRules', 0, new \PHPStan\Type\ObjectType('Cake\\ORM\\RulesChecker')), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Table', 'beforeRules', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Table', 'afterRules', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Table', 'beforeSave', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Table', 'afterSave', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Table', 'beforeDelete', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\ORM\\Table', 'afterDelete', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\Controller\\Controller', 'beforeFilter', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\Controller\\Controller', 'afterFilter', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\Controller\\Controller', 'beforeRender', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\Controller\\Controller', 'beforeRedirect', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\Controller\\Component', 'shutdown', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\Controller\\Component', 'startup', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\Controller\\Component', 'beforeFilter', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\Controller\\Component', 'beforeRender', 0, $eventInterfaceObjectType), new \Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('Cake\\Controller\\Component', 'beforeRedirect', 0, $eventInterfaceObjectType)])]]);
    $services->set(\Rector\CakePHP\Rector\MethodCall\RenameMethodCallBasedOnParameterRector::class)->call('configure', [[\Rector\CakePHP\Rector\MethodCall\RenameMethodCallBasedOnParameterRector::CALLS_WITH_PARAM_RENAMES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\CakePHP\ValueObject\RenameMethodCallBasedOnParameter('Cake\\Http\\ServerRequest', 'getParam', 'paging', 'getAttribute'), new \Rector\CakePHP\ValueObject\RenameMethodCallBasedOnParameter('Cake\\Http\\ServerRequest', 'withParam', 'paging', 'withAttribute')])]]);
    $services->set(\Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector::class)->call('configure', [[\Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector::UNPREFIXED_METHODS_TO_GET_SET => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\CakePHP\ValueObject\ModalToGetSet('Cake\\Console\\ConsoleIo', 'styles', 'setStyle', 'getStyle'), new \Rector\CakePHP\ValueObject\ModalToGetSet('Cake\\Console\\ConsoleOutput', 'styles', 'setStyle', 'getStyle'), new \Rector\CakePHP\ValueObject\ModalToGetSet('Cake\\ORM\\EntityInterface', 'isNew', 'setNew', 'isNew')])]]);
};
