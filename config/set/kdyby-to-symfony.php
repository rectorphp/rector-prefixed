<?php

declare (strict_types=1);
namespace RectorPrefix20201228;

use Rector\Core\ValueObject\Visibility;
use Rector\Generic\Rector\ClassMethod\ChangeMethodVisibilityRector;
use Rector\Generic\ValueObject\ChangeMethodVisibility;
use Rector\NetteToSymfony\Rector\MethodCall\WrapTransParameterNameRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use RectorPrefix20201228\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\RectorPrefix20201228\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Generic\Rector\ClassMethod\ChangeMethodVisibilityRector::class)->call('configure', [[\Rector\Generic\Rector\ClassMethod\ChangeMethodVisibilityRector::METHOD_VISIBILITIES => \RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\ChangeMethodVisibility('RectorPrefix20201228\\Kdyby\\Events\\Subscriber', 'getSubscribedEvents', \Rector\Core\ValueObject\Visibility::STATIC)])]]);
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('RectorPrefix20201228\\Kdyby\\Translation\\Translator', 'translate', 'trans'), new \Rector\Renaming\ValueObject\MethodCallRename('RectorPrefix20201228\\Kdyby\\RabbitMq\\IConsumer', 'process', 'execute')])]]);
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['RectorPrefix20201228\\Kdyby\\RabbitMq\\IConsumer' => 'RectorPrefix20201228\\OldSound\\RabbitMqBundle\\RabbitMq\\ConsumerInterface', 'RectorPrefix20201228\\Kdyby\\RabbitMq\\IProducer' => 'RectorPrefix20201228\\OldSound\\RabbitMqBundle\\RabbitMq\\ProducerInterface', 'RectorPrefix20201228\\Kdyby\\Monolog\\Logger' => 'RectorPrefix20201228\\Psr\\Log\\LoggerInterface', 'RectorPrefix20201228\\Kdyby\\Events\\Subscriber' => 'RectorPrefix20201228\\Symfony\\Component\\EventDispatcher\\EventSubscriberInterface', 'RectorPrefix20201228\\Kdyby\\Translation\\Translator' => 'RectorPrefix20201228\\Symfony\\Contracts\\Translation\\TranslatorInterface']]]);
    $services->set(\Rector\NetteToSymfony\Rector\MethodCall\WrapTransParameterNameRector::class);
};
