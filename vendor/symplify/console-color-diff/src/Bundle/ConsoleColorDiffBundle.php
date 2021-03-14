<?php

declare (strict_types=1);
namespace RectorPrefix20210314\Symplify\ConsoleColorDiff\Bundle;

use RectorPrefix20210314\Symfony\Component\HttpKernel\Bundle\Bundle;
use RectorPrefix20210314\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension;
final class ConsoleColorDiffBundle extends \RectorPrefix20210314\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : \RectorPrefix20210314\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension
    {
        return new \RectorPrefix20210314\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension();
    }
}
