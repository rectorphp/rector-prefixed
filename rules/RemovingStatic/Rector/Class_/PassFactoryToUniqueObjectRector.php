<?php

declare (strict_types=1);
namespace Rector\RemovingStatic\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\PropertyNaming;
use Rector\RemovingStatic\Printer\FactoryClassPrinter;
use Rector\RemovingStatic\StaticTypesInClassResolver;
use Rector\RemovingStatic\UniqueObjectFactoryFactory;
use Rector\RemovingStatic\UniqueObjectOrServiceDetector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\RemovingStatic\Rector\Class_\PassFactoryToEntityRector\PassFactoryToEntityRectorTest
 */
final class PassFactoryToUniqueObjectRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const TYPES_TO_SERVICES = 'types_to_services';
    /**
     * @var ObjectType[]
     */
    private $serviceObjectTypes = [];
    /**
     * @var PropertyNaming
     */
    private $propertyNaming;
    /**
     * @var UniqueObjectOrServiceDetector
     */
    private $uniqueObjectOrServiceDetector;
    /**
     * @var UniqueObjectFactoryFactory
     */
    private $uniqueObjectFactoryFactory;
    /**
     * @var FactoryClassPrinter
     */
    private $factoryClassPrinter;
    /**
     * @var StaticTypesInClassResolver
     */
    private $staticTypesInClassResolver;
    /**
     * @param \Rector\RemovingStatic\StaticTypesInClassResolver $staticTypesInClassResolver
     * @param \Rector\Naming\Naming\PropertyNaming $propertyNaming
     * @param \Rector\RemovingStatic\UniqueObjectOrServiceDetector $uniqueObjectOrServiceDetector
     * @param \Rector\RemovingStatic\UniqueObjectFactoryFactory $uniqueObjectFactoryFactory
     * @param \Rector\RemovingStatic\Printer\FactoryClassPrinter $factoryClassPrinter
     */
    public function __construct($staticTypesInClassResolver, $propertyNaming, $uniqueObjectOrServiceDetector, $uniqueObjectFactoryFactory, $factoryClassPrinter)
    {
        $this->propertyNaming = $propertyNaming;
        $this->uniqueObjectOrServiceDetector = $uniqueObjectOrServiceDetector;
        $this->uniqueObjectFactoryFactory = $uniqueObjectFactoryFactory;
        $this->factoryClassPrinter = $factoryClassPrinter;
        $this->staticTypesInClassResolver = $staticTypesInClassResolver;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Convert new X/Static::call() to factories in entities, pass them via constructor to each other', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
<?php

namespace RectorPrefix20210317;

class SomeClass
{
    public function run()
    {
        return new \RectorPrefix20210317\AnotherClass();
    }
}
\class_alias('SomeClass', 'SomeClass', \false);
class AnotherClass
{
    public function someFun()
    {
        return \RectorPrefix20210317\StaticClass::staticMethod();
    }
}
\class_alias('AnotherClass', 'AnotherClass', \false);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(AnotherClassFactory $anotherClassFactory)
    {
        $this->anotherClassFactory = $anotherClassFactory;
    }

    public function run()
    {
        return $this->anotherClassFactory->create();
    }
}

class AnotherClass
{
    public function __construct(StaticClass $staticClass)
    {
        $this->staticClass = $staticClass;
    }

    public function someFun()
    {
        return $this->staticClass->staticMethod();
    }
}

final class AnotherClassFactory
{
    /**
     * @var StaticClass
     */
    private $staticClass;

    public function __construct(StaticClass $staticClass)
    {
        $this->staticClass = $staticClass;
    }

    public function create(): AnotherClass
    {
        return new AnotherClass($this->staticClass);
    }
}
CODE_SAMPLE
, [self::TYPES_TO_SERVICES => ['StaticClass']])]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Class_::class, \PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param StaticCall|Class_ $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if ($node instanceof \PhpParser\Node\Stmt\Class_) {
            return $this->refactorClass($node);
        }
        foreach ($this->serviceObjectTypes as $serviceObjectType) {
            if (!$this->isObjectType($node->class, $serviceObjectType)) {
                continue;
            }
            // is this object created via new somewhere else? use factory!
            $variableName = $this->propertyNaming->fqnToVariableName($serviceObjectType);
            $thisPropertyFetch = new \PhpParser\Node\Expr\PropertyFetch(new \PhpParser\Node\Expr\Variable('this'), $variableName);
            return new \PhpParser\Node\Expr\MethodCall($thisPropertyFetch, $node->name, $node->args);
        }
        return $node;
    }
    /**
     * @param array<string, mixed[]> $configuration
     */
    public function configure($configuration) : void
    {
        $typesToServices = $configuration[self::TYPES_TO_SERVICES] ?? [];
        foreach ($typesToServices as $typeToService) {
            $this->serviceObjectTypes[] = new \PHPStan\Type\ObjectType($typeToService);
        }
    }
    /**
     * @param \PhpParser\Node\Stmt\Class_ $class
     */
    private function refactorClass($class) : \PhpParser\Node\Stmt\Class_
    {
        $staticTypesInClass = $this->staticTypesInClassResolver->collectStaticCallTypeInClass($class, $this->serviceObjectTypes);
        foreach ($staticTypesInClass as $staticTypeInClass) {
            $variableName = $this->propertyNaming->fqnToVariableName($staticTypeInClass);
            $this->addConstructorDependencyToClass($class, $staticTypeInClass, $variableName);
            // is this an object? create factory for it next to this :)
            if ($this->uniqueObjectOrServiceDetector->isUniqueObject()) {
                $factoryClass = $this->uniqueObjectFactoryFactory->createFactoryClass($class, $staticTypeInClass);
                $this->factoryClassPrinter->printFactoryForClass($factoryClass, $class);
            }
        }
        return $class;
    }
}
