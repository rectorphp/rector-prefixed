<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74;

use _PhpScoperb75b35f52b74\Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use _PhpScoperb75b35f52b74\Rector\Renaming\ValueObject\MethodCallRename;
use _PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use _PhpScoperb75b35f52b74\Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\_PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScoperb75b35f52b74\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\_PhpScoperb75b35f52b74\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \_PhpScoperb75b35f52b74\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoperb75b35f52b74\Rector\Renaming\ValueObject\MethodCallRename('Twig_Node', 'getLine', 'getTemplateLine'), new \_PhpScoperb75b35f52b74\Rector\Renaming\ValueObject\MethodCallRename('Twig_Node', 'getFilename', 'getTemplateName'), new \_PhpScoperb75b35f52b74\Rector\Renaming\ValueObject\MethodCallRename('Twig_Template', 'getSource', 'getSourceContext'), new \_PhpScoperb75b35f52b74\Rector\Renaming\ValueObject\MethodCallRename('Twig_Error', 'getTemplateFile', 'getTemplateName'), new \_PhpScoperb75b35f52b74\Rector\Renaming\ValueObject\MethodCallRename('Twig_Error', 'getTemplateName', 'setTemplateName')])]]);
};
