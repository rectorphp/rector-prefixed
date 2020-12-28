<?php

declare (strict_types=1);
namespace Rector\DeadCode\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use Rector\Core\PhpParser\Node\Manipulator\PropertyManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\VendorLocker\VendorLockResolver;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @sponsor Thanks https://spaceflow.io/ for sponsoring this rule - visit them on https://github.com/SpaceFlow-app
 *
 * @see \Rector\DeadCode\Tests\Rector\Property\RemoveSetterOnlyPropertyAndMethodCallRector\RemoveSetterOnlyPropertyAndMethodCallRectorTest
 */
final class RemoveSetterOnlyPropertyAndMethodCallRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var PropertyManipulator
     */
    private $propertyManipulator;
    /**
     * @var VendorLockResolver
     */
    private $vendorLockResolver;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\PropertyManipulator $propertyManipulator, \Rector\VendorLocker\VendorLockResolver $vendorLockResolver)
    {
        $this->propertyManipulator = $propertyManipulator;
        $this->vendorLockResolver = $vendorLockResolver;
    }
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Removes method that set values that are never used', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    private $name;

    public function setName($name)
    {
        $this->name = $name;
    }
}

class ActiveOnlySetter
{
    public function run()
    {
        $someClass = new SomeClass();
        $someClass->setName('Tom');
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
}

class ActiveOnlySetter
{
    public function run()
    {
        $someClass = new SomeClass();
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Property::class];
    }
    /**
     * @param Property $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($this->shouldSkipProperty($node)) {
            return null;
        }
        $propertyFetches = $this->propertyManipulator->getPrivatePropertyFetches($node);
        $classMethodsToCheck = $this->collectClassMethodsToCheck($propertyFetches);
        $vendorLockedClassMethodNames = $this->getVendorLockedClassMethodNames($classMethodsToCheck);
        $this->removePropertyAndUsages($node, $vendorLockedClassMethodNames);
        /** @var ClassMethod $method */
        foreach ($classMethodsToCheck as $method) {
            if (!$this->hasMethodSomeStmtsLeft($method)) {
                continue;
            }
            $classMethodName = $this->getName($method->name);
            if (\in_array($classMethodName, $vendorLockedClassMethodNames, \true)) {
                continue;
            }
            $this->removeClassMethodAndUsages($method);
        }
        return $node;
    }
    private function shouldSkipProperty(\PhpParser\Node\Stmt\Property $property) : bool
    {
        if (\count($property->props) !== 1) {
            return \true;
        }
        if (!$property->isPrivate()) {
            return \true;
        }
        /** @var Class_|Interface_|Trait_|null $classLike */
        $classLike = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            return \true;
        }
        if ($classLike instanceof \PhpParser\Node\Stmt\Trait_) {
            return \true;
        }
        if ($classLike instanceof \PhpParser\Node\Stmt\Interface_) {
            return \true;
        }
        return $this->propertyManipulator->isPropertyUsedInReadContext($property);
    }
    /**
     * @param PropertyFetch[]|StaticPropertyFetch[] $propertyFetches
     * @return ClassMethod[]
     */
    private function collectClassMethodsToCheck(array $propertyFetches) : array
    {
        $classMethodsToCheck = [];
        foreach ($propertyFetches as $propertyFetch) {
            $methodName = $propertyFetch->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::METHOD_NAME);
            // this rector does not remove empty constructors
            if ($methodName === \Rector\Core\ValueObject\MethodName::CONSTRUCT) {
                continue;
            }
            /** @var ClassMethod|null $classMethod */
            $classMethod = $propertyFetch->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::METHOD_NODE);
            if ($classMethod === null) {
                continue;
            }
            $classMethodsToCheck[$methodName] = $classMethod;
        }
        return $classMethodsToCheck;
    }
    /**
     * @param ClassMethod[] $methodsToCheck
     * @return string[]
     */
    private function getVendorLockedClassMethodNames(array $methodsToCheck) : array
    {
        $vendorLockedClassMethodsNames = [];
        foreach ($methodsToCheck as $method) {
            if (!$this->vendorLockResolver->isClassMethodRemovalVendorLocked($method)) {
                continue;
            }
            $vendorLockedClassMethodsNames[] = $this->getName($method);
        }
        return $vendorLockedClassMethodsNames;
    }
    private function hasMethodSomeStmtsLeft(\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        foreach ((array) $classMethod->stmts as $stmt) {
            if (!$this->isNodeRemoved($stmt)) {
                return \false;
            }
        }
        return \true;
    }
}
