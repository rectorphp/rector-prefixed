<?php

declare (strict_types=1);
namespace RectorPrefix20201230\Symplify\ComposerJsonManipulator\Bundle;

use RectorPrefix20201230\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use RectorPrefix20201230\Symfony\Component\HttpKernel\Bundle\Bundle;
use RectorPrefix20201230\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension;
final class ComposerJsonManipulatorBundle extends \RectorPrefix20201230\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\RectorPrefix20201230\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \RectorPrefix20201230\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension();
    }
}
