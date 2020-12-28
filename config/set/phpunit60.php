<?php

declare (strict_types=1);
namespace RectorPrefix20201228;

use Rector\Generic\ValueObject\PseudoNamespaceToNamespace;
use Rector\PHPUnit\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector;
use Rector\PHPUnit\Rector\MethodCall\GetMockBuilderGetMockToCreateMockRector;
use Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use RectorPrefix20201228\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\RectorPrefix20201228\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $containerConfigurator->import(__DIR__ . '/phpunit-exception.php');
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('RectorPrefix20201228\\PHPUnit\\Framework\\TestCase', 'createMockBuilder', 'getMockBuilder')])]]);
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['PHPUnit_Framework_MockObject_Stub' => 'RectorPrefix20201228\\PHPUnit\\Framework\\MockObject\\Stub', 'PHPUnit_Framework_MockObject_Stub_Return' => 'RectorPrefix20201228\\PHPUnit\\Framework\\MockObject\\Stub\\ReturnStub', 'PHPUnit_Framework_MockObject_Matcher_Parameters' => 'RectorPrefix20201228\\PHPUnit\\Framework\\MockObject\\Matcher\\Parameters', 'PHPUnit_Framework_MockObject_Matcher_Invocation' => 'RectorPrefix20201228\\PHPUnit\\Framework\\MockObject\\Matcher\\Invocation', 'PHPUnit_Framework_MockObject_MockObject' => 'RectorPrefix20201228\\PHPUnit\\Framework\\MockObject\\MockObject', 'PHPUnit_Framework_MockObject_Invocation_Object' => 'RectorPrefix20201228\\PHPUnit\\Framework\\MockObject\\Invocation\\ObjectInvocation']]]);
    $services->set(\Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector::class)->call('configure', [[
        // ref. https://github.com/sebastianbergmann/phpunit/compare/5.7.9...6.0.0
        \Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector::NAMESPACE_PREFIXES_WITH_EXCLUDED_CLASSES => \RectorPrefix20201228\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\PseudoNamespaceToNamespace('PHPUnit_', ['PHPUnit_Framework_MockObject_MockObject', 'PHPUnit_Framework_MockObject_Invocation_Object', 'PHPUnit_Framework_MockObject_Matcher_Invocation', 'PHPUnit_Framework_MockObject_Matcher_Parameters', 'PHPUnit_Framework_MockObject_Stub_Return', 'PHPUnit_Framework_MockObject_Stub'])]),
    ]]);
    $services->set(\Rector\PHPUnit\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector::class);
    $services->set(\Rector\PHPUnit\Rector\MethodCall\GetMockBuilderGetMockToCreateMockRector::class);
};
