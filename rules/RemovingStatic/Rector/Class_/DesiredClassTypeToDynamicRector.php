<?php

declare (strict_types=1);
namespace Rector\RemovingStatic\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Core\Configuration\Option;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Naming\Naming\PropertyNaming;
use Rector\RemovingStatic\NodeAnalyzer\StaticCallPresenceAnalyzer;
use RectorPrefix20210317\Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\RemovingStatic\Rector\Class_\DesiredClassTypeToDynamicRector\DesiredClassTypeToDynamicRectorTest
 */
final class DesiredClassTypeToDynamicRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var ObjectType[]
     */
    private $staticObjectTypes = [];
    /**
     * @var PropertyNaming
     */
    private $propertyNaming;
    /**
     * @var StaticCallPresenceAnalyzer
     */
    private $staticCallPresenceAnalyzer;
    /**
     * @param \Rector\Naming\Naming\PropertyNaming $propertyNaming
     * @param \Rector\RemovingStatic\NodeAnalyzer\StaticCallPresenceAnalyzer $staticCallPresenceAnalyzer
     * @param \Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider
     */
    public function __construct($propertyNaming, $staticCallPresenceAnalyzer, $parameterProvider)
    {
        $typesToRemoveStaticFrom = $parameterProvider->provideArrayParameter(\Rector\Core\Configuration\Option::TYPES_TO_REMOVE_STATIC_FROM);
        foreach ($typesToRemoveStaticFrom as $typeToRemoveStaticFrom) {
            $this->staticObjectTypes[] = new \PHPStan\Type\ObjectType($typeToRemoveStaticFrom);
        }
        $this->propertyNaming = $propertyNaming;
        $this->staticCallPresenceAnalyzer = $staticCallPresenceAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change full static service, to dynamic one', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class AnotherClass
{
    public function run()
    {
        SomeClass::someStatic();
    }
}

class SomeClass
{
    public static function run()
    {
        self::someStatic();
    }

    private static function someStatic()
    {
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class AnotherClass
{
    /**
     * @var SomeClass
     */
    private $someClass;

    public fuction __construct(SomeClass $someClass)
    {
        $this->someClass = $someClass;
    }

    public function run()
    {
        SomeClass::someStatic();
    }
}

class SomeClass
{
    public function run()
    {
        $this->someStatic();
    }

    private function someStatic()
    {
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        foreach ($this->staticObjectTypes as $staticObjectType) {
            // do not any dependencies to class itself
            if ($this->isObjectType($node, $staticObjectType)) {
                continue;
            }
            $this->completeDependencyToConstructorOnly($node, $staticObjectType);
            if ($this->staticCallPresenceAnalyzer->hasClassAnyMethodWithStaticCallOnType($node, $staticObjectType)) {
                $propertyExpectedName = $this->propertyNaming->fqnToVariableName($staticObjectType);
                $this->addConstructorDependencyToClass($node, $staticObjectType, $propertyExpectedName);
                return $node;
            }
        }
        return null;
    }
    /**
     * @param \PhpParser\Node\Stmt\Class_ $class
     * @param \PHPStan\Type\ObjectType $objectType
     */
    private function completeDependencyToConstructorOnly($class, $objectType) : void
    {
        $constructClassMethod = $class->getMethod(\Rector\Core\ValueObject\MethodName::CONSTRUCT);
        if (!$constructClassMethod instanceof \PhpParser\Node\Stmt\ClassMethod) {
            return;
        }
        $hasStaticCall = $this->staticCallPresenceAnalyzer->hasMethodStaticCallOnType($constructClassMethod, $objectType);
        if (!$hasStaticCall) {
            return;
        }
        $propertyExpectedName = $this->propertyNaming->fqnToVariableName($objectType);
        if ($this->isTypeAlreadyInParamMethod($constructClassMethod, $objectType)) {
            return;
        }
        $constructClassMethod->params[] = $this->createParam($propertyExpectedName, $objectType);
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     * @param \PHPStan\Type\ObjectType $objectType
     */
    private function isTypeAlreadyInParamMethod($classMethod, $objectType) : bool
    {
        foreach ($classMethod->getParams() as $param) {
            if ($param->type === null) {
                continue;
            }
            if ($this->isName($param->type, $objectType->getClassName())) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @param string $propertyName
     * @param \PHPStan\Type\ObjectType $objectType
     */
    private function createParam($propertyName, $objectType) : \PhpParser\Node\Param
    {
        return new \PhpParser\Node\Param(new \PhpParser\Node\Expr\Variable($propertyName), null, new \PhpParser\Node\Name\FullyQualified($objectType->getClassName()));
    }
}
