<?php

declare (strict_types=1);
namespace Rector\Core\NodeManipulator;

use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PostRector\Collector\NodesToRemoveCollector;
final class ClassManipulator
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var NodesToRemoveCollector
     */
    private $nodesToRemoveCollector;
    public function __construct(\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \Rector\PostRector\Collector\NodesToRemoveCollector $nodesToRemoveCollector)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->nodesToRemoveCollector = $nodesToRemoveCollector;
    }
    /**
     * @param Class_|Trait_ $classLike
     * @return array<string, Name>
     */
    public function getUsedTraits(\PhpParser\Node\Stmt\ClassLike $classLike) : array
    {
        $usedTraits = [];
        foreach ($classLike->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $trait) {
                /** @var string $traitName */
                $traitName = $this->nodeNameResolver->getName($trait);
                $usedTraits[$traitName] = $trait;
            }
        }
        return $usedTraits;
    }
    public function hasParentMethodOrInterface(string $class, string $method) : bool
    {
        if (!\class_exists($class)) {
            return \false;
        }
        $parentClass = $class;
        while ($parentClass = \get_parent_class($parentClass)) {
            if (\method_exists($parentClass, $method)) {
                return \true;
            }
        }
        $implementedInterfaces = (array) \class_implements($class);
        foreach ($implementedInterfaces as $implementedInterface) {
            /** @var string $implementedInterface */
            if (\method_exists($implementedInterface, $method)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @return string[]
     */
    public function getPrivatePropertyNames(\PhpParser\Node\Stmt\Class_ $class) : array
    {
        $privateProperties = \array_filter($class->getProperties(), function (\PhpParser\Node\Stmt\Property $property) : bool {
            return $property->isPrivate();
        });
        return $this->nodeNameResolver->getNames($privateProperties);
    }
    /**
     * @return string[]
     */
    public function getPublicMethodNames(\PhpParser\Node\Stmt\Class_ $class) : array
    {
        $publicMethods = \array_filter($class->getMethods(), function (\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool {
            if ($classMethod->isAbstract()) {
                return \false;
            }
            return $classMethod->isPublic();
        });
        return $this->nodeNameResolver->getNames($publicMethods);
    }
    /**
     * @return string[]
     */
    public function getImplementedInterfaceNames(\PhpParser\Node\Stmt\Class_ $class) : array
    {
        return $this->nodeNameResolver->getNames($class->implements);
    }
    public function hasInterface(\PhpParser\Node\Stmt\Class_ $class, string $desiredInterface) : bool
    {
        return $this->nodeNameResolver->isName($class->implements, $desiredInterface);
    }
    public function hasTrait(\PhpParser\Node\Stmt\Class_ $class, string $desiredTrait) : bool
    {
        foreach ($class->getTraitUses() as $traitUse) {
            if (!$this->nodeNameResolver->isName($traitUse->traits, $desiredTrait)) {
                continue;
            }
            return \true;
        }
        return \false;
    }
    public function replaceTrait(\PhpParser\Node\Stmt\Class_ $class, string $oldTrait, string $newTrait) : void
    {
        foreach ($class->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $key => $traitTrait) {
                if (!$this->nodeNameResolver->isName($traitTrait, $oldTrait)) {
                    continue;
                }
                $traitUse->traits[$key] = new \PhpParser\Node\Name\FullyQualified($newTrait);
                break;
            }
        }
    }
    /**
     * @param Class_|Interface_ $classLike
     * @return string[]
     */
    public function getClassLikeNodeParentInterfaceNames(\PhpParser\Node\Stmt\ClassLike $classLike) : array
    {
        if ($classLike instanceof \PhpParser\Node\Stmt\Class_) {
            return $this->nodeNameResolver->getNames($classLike->implements);
        }
        return $this->nodeNameResolver->getNames($classLike->extends);
    }
    public function removeInterface(\PhpParser\Node\Stmt\Class_ $class, string $desiredInterface) : void
    {
        foreach ($class->implements as $implement) {
            if (!$this->nodeNameResolver->isName($implement, $desiredInterface)) {
                continue;
            }
            $this->nodesToRemoveCollector->addNodeToRemove($implement);
        }
    }
}
