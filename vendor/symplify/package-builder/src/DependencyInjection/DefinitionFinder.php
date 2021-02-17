<?php

declare (strict_types=1);
namespace RectorPrefix20210217\Symplify\PackageBuilder\DependencyInjection;

use RectorPrefix20210217\Symfony\Component\DependencyInjection\ContainerBuilder;
use RectorPrefix20210217\Symfony\Component\DependencyInjection\Definition;
use RectorPrefix20210217\Symplify\PackageBuilder\Exception\DependencyInjection\DefinitionForTypeNotFoundException;
use Throwable;
/**
 * @see \Symplify\PackageBuilder\Tests\DependencyInjection\DefinitionFinderTest
 */
final class DefinitionFinder
{
    /**
     * @return Definition[]
     */
    public function findAllByType(\RectorPrefix20210217\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $type) : array
    {
        $definitions = [];
        $containerBuilderDefinitions = $containerBuilder->getDefinitions();
        foreach ($containerBuilderDefinitions as $name => $definition) {
            $class = $definition->getClass() ?: $name;
            if (!$this->isClassExists($class)) {
                continue;
            }
            if (\is_a($class, $type, \true)) {
                $definitions[$name] = $definition;
            }
        }
        return $definitions;
    }
    public function getByType(\RectorPrefix20210217\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $type) : \RectorPrefix20210217\Symfony\Component\DependencyInjection\Definition
    {
        $definition = $this->getByTypeIfExists($containerBuilder, $type);
        if ($definition !== null) {
            return $definition;
        }
        throw new \RectorPrefix20210217\Symplify\PackageBuilder\Exception\DependencyInjection\DefinitionForTypeNotFoundException(\sprintf('Definition for type "%s" was not found.', $type));
    }
    private function getByTypeIfExists(\RectorPrefix20210217\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $type) : ?\RectorPrefix20210217\Symfony\Component\DependencyInjection\Definition
    {
        $containerBuilderDefinitions = $containerBuilder->getDefinitions();
        foreach ($containerBuilderDefinitions as $name => $definition) {
            $class = $definition->getClass() ?: $name;
            if (!$this->isClassExists($class)) {
                continue;
            }
            if (\is_a($class, $type, \true)) {
                return $definition;
            }
        }
        return null;
    }
    private function isClassExists(?string $class) : bool
    {
        try {
            if (!\is_string($class)) {
                return \false;
            }
            return \class_exists($class);
        } catch (\Throwable $throwable) {
            return \false;
        }
    }
}
