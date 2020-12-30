<?php

declare (strict_types=1);
namespace Rector\PHPUnit\Tests\Rector\Class_\SelfContainerGetMethodCallFromTestToInjectPropertyRector\Source;

use RectorPrefix20201230\PHPUnit\Framework\TestCase;
use RectorPrefix20201230\Symfony\Component\DependencyInjection\ContainerInterface;
abstract class TestCaseWithGetContainer extends \RectorPrefix20201230\PHPUnit\Framework\TestCase
{
    public function getContainer() : \RectorPrefix20201230\Symfony\Component\DependencyInjection\ContainerInterface
    {
    }
}
