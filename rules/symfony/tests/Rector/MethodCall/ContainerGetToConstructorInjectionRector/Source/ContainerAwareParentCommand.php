<?php

declare (strict_types=1);
namespace Rector\Symfony\Tests\Rector\MethodCall\ContainerGetToConstructorInjectionRector\Source;

use _PhpScoper88fe6e0ad041\Symfony\Component\Console\Command\Command;
use _PhpScoper88fe6e0ad041\Symfony\Component\DependencyInjection\ContainerInterface;
class ContainerAwareParentCommand extends \_PhpScoper88fe6e0ad041\Symfony\Component\Console\Command\Command
{
    public function getContainer() : \_PhpScoper88fe6e0ad041\Symfony\Component\DependencyInjection\ContainerInterface
    {
    }
}
