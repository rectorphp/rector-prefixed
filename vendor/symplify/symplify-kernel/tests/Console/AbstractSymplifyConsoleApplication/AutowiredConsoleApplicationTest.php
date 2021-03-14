<?php

declare (strict_types=1);
namespace RectorPrefix20210314\Symplify\SymplifyKernel\Tests\Console\AbstractSymplifyConsoleApplication;

use RectorPrefix20210314\Symfony\Component\Console\Application;
use RectorPrefix20210314\Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use RectorPrefix20210314\Symplify\SymplifyKernel\Tests\HttpKernel\OnlyForTestsKernel;
final class AutowiredConsoleApplicationTest extends \RectorPrefix20210314\Symplify\PackageBuilder\Testing\AbstractKernelTestCase
{
    protected function setUp() : void
    {
        $this->bootKernel(\RectorPrefix20210314\Symplify\SymplifyKernel\Tests\HttpKernel\OnlyForTestsKernel::class);
    }
    public function test() : void
    {
        $application = $this->getService(\RectorPrefix20210314\Symfony\Component\Console\Application::class);
        $this->assertInstanceOf(\RectorPrefix20210314\Symfony\Component\Console\Application::class, $application);
    }
}
