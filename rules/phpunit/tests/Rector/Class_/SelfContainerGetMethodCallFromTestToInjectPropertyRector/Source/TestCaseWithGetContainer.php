<?php

declare (strict_types=1);
namespace Rector\PHPUnit\Tests\Rector\Class_\SelfContainerGetMethodCallFromTestToInjectPropertyRector\Source;

use RectorPrefix20210111\PHPUnit\Framework\TestCase;
use RectorPrefix20210111\Symfony\Component\DependencyInjection\ContainerInterface;
abstract class TestCaseWithGetContainer extends \RectorPrefix20210111\PHPUnit\Framework\TestCase
{
    public function getContainer() : \RectorPrefix20210111\Symfony\Component\DependencyInjection\ContainerInterface
    {
    }
}
