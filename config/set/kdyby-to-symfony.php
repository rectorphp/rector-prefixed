<?php

declare (strict_types=1);
namespace _PhpScoper5b8c9e9ebd21;

use Rector\Core\ValueObject\Visibility;
use Rector\Generic\Rector\ClassMethod\ChangeMethodVisibilityRector;
use Rector\Generic\ValueObject\ChangeMethodVisibility;
use Rector\NetteToSymfony\Rector\MethodCall\WrapTransParameterNameRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Generic\Rector\ClassMethod\ChangeMethodVisibilityRector::class)->call('configure', [[\Rector\Generic\Rector\ClassMethod\ChangeMethodVisibilityRector::METHOD_VISIBILITIES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\ChangeMethodVisibility('_PhpScoper5b8c9e9ebd21\\Kdyby\\Events\\Subscriber', 'getSubscribedEvents', \Rector\Core\ValueObject\Visibility::STATIC)])]]);
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper5b8c9e9ebd21\\Kdyby\\Translation\\Translator', 'translate', 'trans'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper5b8c9e9ebd21\\Kdyby\\RabbitMq\\IConsumer', 'process', 'execute')])]]);
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoper5b8c9e9ebd21\\Kdyby\\RabbitMq\\IConsumer' => '_PhpScoper5b8c9e9ebd21\\OldSound\\RabbitMqBundle\\RabbitMq\\ConsumerInterface', '_PhpScoper5b8c9e9ebd21\\Kdyby\\RabbitMq\\IProducer' => '_PhpScoper5b8c9e9ebd21\\OldSound\\RabbitMqBundle\\RabbitMq\\ProducerInterface', '_PhpScoper5b8c9e9ebd21\\Kdyby\\Monolog\\Logger' => '_PhpScoper5b8c9e9ebd21\\Psr\\Log\\LoggerInterface', '_PhpScoper5b8c9e9ebd21\\Kdyby\\Events\\Subscriber' => '_PhpScoper5b8c9e9ebd21\\Symfony\\Component\\EventDispatcher\\EventSubscriberInterface', '_PhpScoper5b8c9e9ebd21\\Kdyby\\Translation\\Translator' => '_PhpScoper5b8c9e9ebd21\\Symfony\\Contracts\\Translation\\TranslatorInterface']]]);
    $services->set(\Rector\NetteToSymfony\Rector\MethodCall\WrapTransParameterNameRector::class);
};
