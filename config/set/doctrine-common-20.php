<?php

declare (strict_types=1);
namespace _PhpScoper88fe6e0ad041;

use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
# see https://github.com/doctrine/persistence/pull/71
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Event\\LifecycleEventArgs' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Event\\LifecycleEventArgs', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Event\\LoadClassMetadataEventArgs' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Event\\LoadClassMetadataEventArgs', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Event\\ManagerEventArgs' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Event\\ManagerEventArgs', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\AbstractClassMetadataFactory' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\AbstractClassMetadataFactory', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\ClassMetadata' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\ClassMetadata', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\ClassMetadataFactory' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\ClassMetadataFactory', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\FileDriver' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\Driver\\FileDriver', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\MappingDriver' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\Driver\\MappingDriver', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\MappingDriverChain' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\Driver\\MappingDriverChain', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\PHPDriver' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\Driver\\PHPDriver', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\StaticPHPDriver' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\Driver\\StaticPHPDriver', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\SymfonyFileLocator' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\Driver\\SymfonyFileLocator', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\MappingException' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\MappingException', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\ReflectionService' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\ReflectionService', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\RuntimeReflectionService' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\RuntimeReflectionService', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\StaticReflectionService' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\StaticReflectionService', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\ObjectManager' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\ObjectManager', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\ObjectManagerDecorator' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\ObjectManagerDecorator', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\ObjectRepository' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\ObjectRepository', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Proxy' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Proxy', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\AbstractManagerRegistry' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\AbstractManagerRegistry', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\DefaultFileLocator' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\Mapping\\Driver\\DefaultFileLocator', '_PhpScoper88fe6e0ad041\\Doctrine\\Common\\Persistence\\ManagerRegistry' => '_PhpScoper88fe6e0ad041\\Doctrine\\Persistence\\ManagerRegistry']]]);
};
