<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\Reflection\BetterReflection\SourceLocator;

class OptimizedDirectorySourceLocatorRepository
{
    /** @var \PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedDirectorySourceLocatorFactory */
    private $factory;
    /** @var array<string, OptimizedDirectorySourceLocator> */
    private $locators = [];
    public function __construct(\_PhpScopere8e811afab72\PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedDirectorySourceLocatorFactory $factory)
    {
        $this->factory = $factory;
    }
    public function getOrCreate(string $directory) : \_PhpScopere8e811afab72\PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedDirectorySourceLocator
    {
        if (\array_key_exists($directory, $this->locators)) {
            return $this->locators[$directory];
        }
        $this->locators[$directory] = $this->factory->create($directory);
        return $this->locators[$directory];
    }
}
