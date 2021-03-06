<?php

declare (strict_types=1);
namespace Rector\RemovingStatic\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\NodeManipulator\ClassInsertManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Naming\Naming\PropertyNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPUnit\NodeFactory\SetUpClassMethodFactory;
use Rector\RemovingStatic\NodeFactory\SelfContainerFactory;
use Rector\RemovingStatic\NodeFactory\SetUpFactory;
use Rector\RemovingStatic\ValueObject\PHPUnitClass;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\RemovingStatic\Rector\Class_\PHPUnitStaticToKernelTestCaseGetRector\PHPUnitStaticToKernelTestCaseGetRectorTest
 */
final class PHPUnitStaticToKernelTestCaseGetRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const STATIC_CLASS_TYPES = 'static_class_types';
    /**
     * @var ObjectType[]
     */
    private $staticObjectTypes = [];
    /**
     * @var ObjectType[]
     */
    private $newPropertyObjectTypes = [];
    /**
     * @var PropertyNaming
     */
    private $propertyNaming;
    /**
     * @var ClassInsertManipulator
     */
    private $classInsertManipulator;
    /**
     * @var SetUpClassMethodFactory
     */
    private $setUpClassMethodFactory;
    /**
     * @var SetUpFactory
     */
    private $setUpFactory;
    /**
     * @var SelfContainerFactory
     */
    private $selfContainerFactory;
    /**
     * @param \Rector\Naming\Naming\PropertyNaming $propertyNaming
     * @param \Rector\Core\NodeManipulator\ClassInsertManipulator $classInsertManipulator
     * @param \Rector\PHPUnit\NodeFactory\SetUpClassMethodFactory $setUpClassMethodFactory
     * @param \Rector\RemovingStatic\NodeFactory\SetUpFactory $setUpFactory
     * @param \Rector\RemovingStatic\NodeFactory\SelfContainerFactory $selfContainerFactory
     */
    public function __construct($propertyNaming, $classInsertManipulator, $setUpClassMethodFactory, $setUpFactory, $selfContainerFactory)
    {
        $this->propertyNaming = $propertyNaming;
        $this->classInsertManipulator = $classInsertManipulator;
        $this->setUpClassMethodFactory = $setUpClassMethodFactory;
        $this->setUpFactory = $setUpFactory;
        $this->selfContainerFactory = $selfContainerFactory;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Convert static calls in PHPUnit test cases, to get() from the container of KernelTestCase', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
<?php

namespace RectorPrefix20210317;

use RectorPrefix20210317\PHPUnit\Framework\TestCase;
final class SomeTestCase extends \RectorPrefix20210317\PHPUnit\Framework\TestCase
{
    public function test()
    {
        $product = \RectorPrefix20210317\EntityFactory::create('product');
    }
}
\class_alias('SomeTestCase', 'SomeTestCase', \false);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SomeTestCase extends KernelTestCase
{
    /**
     * @var EntityFactory
     */
    private $entityFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entityFactory = $this->getService(EntityFactory::class);
    }

    public function test()
    {
        $product = $this->entityFactory->create('product');
    }
}
CODE_SAMPLE
, [self::STATIC_CLASS_TYPES => ['EntityFactory']])]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\StaticCall::class, \PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param StaticCall|Class_ $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        // skip yourself
        $this->newPropertyObjectTypes = [];
        if ($node instanceof \PhpParser\Node\Stmt\Class_) {
            if ($this->nodeTypeResolver->isObjectTypes($node, $this->staticObjectTypes)) {
                return null;
            }
            return $this->processClass($node);
        }
        return $this->processStaticCall($node);
    }
    /**
     * @param array<string, mixed> $configuration
     */
    public function configure($configuration) : void
    {
        $staticClassTypes = $configuration[self::STATIC_CLASS_TYPES] ?? [];
        foreach ($staticClassTypes as $staticClassType) {
            $this->staticObjectTypes[] = new \PHPStan\Type\ObjectType($staticClassType);
        }
    }
    /**
     * @param \PhpParser\Node\Stmt\Class_ $class
     */
    private function processClass($class) : ?\PhpParser\Node\Stmt\Class_
    {
        if ($this->isObjectType($class, new \PHPStan\Type\ObjectType(\Rector\RemovingStatic\ValueObject\PHPUnitClass::TEST_CASE))) {
            return $this->processPHPUnitClass($class);
        }
        // add property with the object
        $newPropertyObjectTypes = $this->collectNewPropertyObjectTypes($class);
        if ($newPropertyObjectTypes === []) {
            return null;
        }
        // add via constructor
        foreach ($newPropertyObjectTypes as $newPropertyObjectType) {
            $newPropertyName = $this->propertyNaming->fqnToVariableName($newPropertyObjectType);
            $this->addConstructorDependencyToClass($class, $newPropertyObjectType, $newPropertyName);
        }
        return $class;
    }
    /**
     * @param \PhpParser\Node\Expr\StaticCall $staticCall
     */
    private function processStaticCall($staticCall) : ?\PhpParser\Node\Expr\MethodCall
    {
        $classLike = $staticCall->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
            return null;
        }
        foreach ($this->staticObjectTypes as $staticObjectType) {
            if (!$this->isObjectType($staticCall->class, $staticObjectType)) {
                continue;
            }
            return $this->convertStaticCallToPropertyMethodCall($staticCall, $staticObjectType);
        }
        return null;
    }
    /**
     * @param \PhpParser\Node\Stmt\Class_ $class
     */
    private function processPHPUnitClass($class) : ?\PhpParser\Node\Stmt\Class_
    {
        // add property with the object
        $newPropertyTypes = $this->collectNewPropertyObjectTypes($class);
        if ($newPropertyTypes === []) {
            return null;
        }
        // add all properties to class
        $class = $this->addNewPropertiesToClass($class, $newPropertyTypes);
        $parentSetUpStaticCallExpression = $this->setUpFactory->createParentStaticCall();
        foreach ($newPropertyTypes as $newPropertyType) {
            // container fetch assign
            $assign = $this->createContainerGetTypeToPropertyAssign($newPropertyType);
            $setupClassMethod = $class->getMethod(\Rector\Core\ValueObject\MethodName::SET_UP);
            // get setup or create a setup add add it there
            if ($setupClassMethod !== null) {
                $this->updateSetUpMethod($setupClassMethod, $parentSetUpStaticCallExpression, $assign);
            } else {
                $setUpMethod = $this->setUpClassMethodFactory->createSetUpMethod([$assign]);
                $this->classInsertManipulator->addAsFirstMethod($class, $setUpMethod);
            }
        }
        // update parent clsas if not already
        if (!$this->isObjectType($class, new \PHPStan\Type\ObjectType('Symfony\\Bundle\\FrameworkBundle\\Test\\KernelTestCase'))) {
            $class->extends = new \PhpParser\Node\Name\FullyQualified('Symfony\\Bundle\\FrameworkBundle\\Test\\KernelTestCase');
        }
        return $class;
    }
    /**
     * @return ObjectType[]
     * @param \PhpParser\Node\Stmt\Class_ $class
     */
    private function collectNewPropertyObjectTypes($class) : array
    {
        $this->newPropertyObjectTypes = [];
        $this->traverseNodesWithCallable($class->stmts, function (\PhpParser\Node $node) : void {
            if (!$node instanceof \PhpParser\Node\Expr\StaticCall) {
                return;
            }
            foreach ($this->staticObjectTypes as $staticObjectType) {
                if (!$this->isObjectType($node->class, $staticObjectType)) {
                    continue;
                }
                $this->newPropertyObjectTypes[] = $staticObjectType;
            }
        });
        $this->newPropertyObjectTypes = \array_unique($this->newPropertyObjectTypes);
        return $this->newPropertyObjectTypes;
    }
    /**
     * @param \PhpParser\Node\Expr\StaticCall $staticCall
     * @param \PHPStan\Type\ObjectType $objectType
     */
    private function convertStaticCallToPropertyMethodCall($staticCall, $objectType) : \PhpParser\Node\Expr\MethodCall
    {
        // create "$this->someService" instead
        $propertyName = $this->propertyNaming->fqnToVariableName($objectType);
        $propertyFetch = new \PhpParser\Node\Expr\PropertyFetch(new \PhpParser\Node\Expr\Variable('this'), $propertyName);
        // turn static call to method on property call
        $methodCall = new \PhpParser\Node\Expr\MethodCall($propertyFetch, $staticCall->name);
        $methodCall->args = $staticCall->args;
        return $methodCall;
    }
    /**
     * @param ObjectType[] $newProperties
     * @param \PhpParser\Node\Stmt\Class_ $class
     */
    private function addNewPropertiesToClass($class, $newProperties) : \PhpParser\Node\Stmt\Class_
    {
        $properties = [];
        foreach ($newProperties as $newProperty) {
            $properties[] = $this->createPropertyFromType($newProperty);
        }
        // add property to the start of the class
        $class->stmts = \array_merge($properties, $class->stmts);
        return $class;
    }
    /**
     * @param \PHPStan\Type\ObjectType $objectType
     */
    private function createContainerGetTypeToPropertyAssign($objectType) : \PhpParser\Node\Stmt\Expression
    {
        $getMethodCall = $this->selfContainerFactory->createGetTypeMethodCall($objectType);
        $propertyName = $this->propertyNaming->fqnToVariableName($objectType);
        $propertyFetch = new \PhpParser\Node\Expr\PropertyFetch(new \PhpParser\Node\Expr\Variable('this'), $propertyName);
        $assign = new \PhpParser\Node\Expr\Assign($propertyFetch, $getMethodCall);
        return new \PhpParser\Node\Stmt\Expression($assign);
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $setupClassMethod
     * @param \PhpParser\Node\Stmt\Expression $parentSetupStaticCall
     * @param \PhpParser\Node\Stmt\Expression $assign
     */
    private function updateSetUpMethod($setupClassMethod, $parentSetupStaticCall, $assign) : void
    {
        $parentSetUpStaticCallPosition = $this->getParentSetUpStaticCallPosition($setupClassMethod);
        if ($parentSetUpStaticCallPosition === null) {
            $setupClassMethod->stmts = \array_merge([$parentSetupStaticCall, $assign], (array) $setupClassMethod->stmts);
        } else {
            \assert($setupClassMethod->stmts !== null);
            \array_splice($setupClassMethod->stmts, $parentSetUpStaticCallPosition + 1, 0, [$assign]);
        }
    }
    /**
     * @param \PHPStan\Type\ObjectType $objectType
     */
    private function createPropertyFromType($objectType) : \PhpParser\Node\Stmt\Property
    {
        $propertyName = $this->propertyNaming->fqnToVariableName($objectType);
        return $this->nodeFactory->createPrivatePropertyFromNameAndType($propertyName, $objectType);
    }
    //    private function createContainerGetTypeMethodCall(ObjectType $objectType): MethodCall
    //    {
    //        $staticPropertyFetch = new StaticPropertyFetch(new Name('self'), 'container');
    //        $getMethodCall = new MethodCall($staticPropertyFetch, 'get');
    //
    //        $className = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($objectType);
    //        if (! $className instanceof Name) {
    //            throw new ShouldNotHappenException();
    //        }
    //
    //        $getMethodCall->args[] = new Arg(new ClassConstFetch($className, 'class'));
    //
    //        return $getMethodCall;
    //    }
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $setupClassMethod
     */
    private function getParentSetUpStaticCallPosition($setupClassMethod) : ?int
    {
        foreach ((array) $setupClassMethod->stmts as $position => $methodStmt) {
            if ($methodStmt instanceof \PhpParser\Node\Stmt\Expression) {
                $methodStmt = $methodStmt->expr;
            }
            if (!$this->nodeNameResolver->isStaticCallNamed($methodStmt, 'parent', \Rector\Core\ValueObject\MethodName::SET_UP)) {
                continue;
            }
            return $position;
        }
        return null;
    }
}
