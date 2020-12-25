<?php

declare (strict_types=1);
namespace Rector\SOLID\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use ReflectionClass;
use ReflectionMethod;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\SOLID\Tests\Rector\ClassMethod\UseInterfaceOverImplementationInConstructorRector\UseInterfaceOverImplementationInConstructorRectorTest
 */
final class UseInterfaceOverImplementationInConstructorRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Use interface instead of specific class', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(SomeImplementation $someImplementation)
    {
    }
}

class SomeImplementation implements SomeInterface
{
}

interface SomeInterface
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(SomeInterface $someImplementation)
    {
    }
}

class SomeImplementation implements SomeInterface
{
}

interface SomeInterface
{
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isName($node, \Rector\Core\ValueObject\MethodName::CONSTRUCT)) {
            return null;
        }
        foreach ($node->params as $param) {
            if ($param->type === null) {
                continue;
            }
            $typeName = $this->getName($param->type);
            if ($typeName === null) {
                continue;
            }
            if (!\class_exists($typeName)) {
                continue;
            }
            $implementedInterfaces = \class_implements($typeName);
            if ($implementedInterfaces === []) {
                continue;
            }
            $interfaceNames = $this->getClassDirectInterfaces($typeName);
            $interfaceNames = $this->filterOutInterfaceThatHaveTwoAndMoreImplementers($interfaceNames);
            if (\count($interfaceNames) !== 1) {
                continue;
            }
            if ($this->hasClassWiderPublicApiThanInterface($typeName, $interfaceNames[0])) {
                continue;
            }
            $param->type = new \PhpParser\Node\Name\FullyQualified($interfaceNames[0]);
        }
        return $node;
    }
    /**
     * @return string[]
     */
    private function getClassDirectInterfaces(string $typeName) : array
    {
        /** @var string[] $interfaceNames */
        $interfaceNames = (array) \class_implements($typeName);
        foreach ($interfaceNames as $possibleDirectInterfaceName) {
            foreach ($interfaceNames as $key => $interfaceName) {
                if ($possibleDirectInterfaceName === $interfaceName) {
                    continue;
                }
                if (!\is_a($possibleDirectInterfaceName, $interfaceName, \true)) {
                    continue;
                }
                unset($interfaceNames[$key]);
            }
        }
        return \array_values($interfaceNames);
    }
    /**
     * 2 and more classes that implement the same interface → better skip it, the particular implementation is used on purpose probably
     *
     * @param string[] $interfaceNames
     * @return string[]
     */
    private function filterOutInterfaceThatHaveTwoAndMoreImplementers(array $interfaceNames) : array
    {
        $classes = \get_declared_classes();
        foreach ($interfaceNames as $key => $interfaceName) {
            $implementations = [];
            foreach ($classes as $class) {
                $interfacesImplementedByClass = (array) \class_implements($class);
                if (!\in_array($interfaceName, $interfacesImplementedByClass, \true)) {
                    continue;
                }
                $implementations[] = $class;
            }
            if (\count($implementations) > 1) {
                unset($interfaceNames[$key]);
            }
        }
        return \array_values($interfaceNames);
    }
    private function hasClassWiderPublicApiThanInterface(string $className, string $interfaceName) : bool
    {
        $classMethods = $this->getPublicMethods($className);
        $interfaceMethods = $this->getPublicMethods($interfaceName);
        return \array_diff($classMethods, $interfaceMethods) !== [];
    }
    /**
     * @param string $fqcn Fully qualified class/interface name
     *
     * @return string[]
     */
    private function getPublicMethods(string $fqcn) : array
    {
        $reflectionClass = new \ReflectionClass($fqcn);
        return \array_map(static function (\ReflectionMethod $method) : string {
            return $method->name;
        }, $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC));
    }
}
