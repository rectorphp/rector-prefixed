<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72;

use _PhpScopere8e811afab72\Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use _PhpScopere8e811afab72\Rector\Renaming\ValueObject\MethodCallRename;
use _PhpScopere8e811afab72\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use _PhpScopere8e811afab72\Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\_PhpScopere8e811afab72\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScopere8e811afab72\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\_PhpScopere8e811afab72\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \_PhpScopere8e811afab72\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScopere8e811afab72\Rector\Renaming\ValueObject\MethodCallRename('Twig_Node', 'getLine', 'getTemplateLine'), new \_PhpScopere8e811afab72\Rector\Renaming\ValueObject\MethodCallRename('Twig_Node', 'getFilename', 'getTemplateName'), new \_PhpScopere8e811afab72\Rector\Renaming\ValueObject\MethodCallRename('Twig_Template', 'getSource', 'getSourceContext'), new \_PhpScopere8e811afab72\Rector\Renaming\ValueObject\MethodCallRename('Twig_Error', 'getTemplateFile', 'getTemplateName'), new \_PhpScopere8e811afab72\Rector\Renaming\ValueObject\MethodCallRename('Twig_Error', 'getTemplateName', 'setTemplateName')])]]);
};
