<?php

declare (strict_types=1);
namespace Rector\RemovingStatic\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\PropertyNaming;
use RectorPrefix20210317\Symplify\Astral\ValueObject\NodeBuilder\MethodBuilder;
use RectorPrefix20210317\Symplify\Astral\ValueObject\NodeBuilder\ParamBuilder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\RemovingStatic\Rector\Class_\StaticTypeToSetterInjectionRector\StaticTypeToSetterInjectionRectorTest
 */
final class StaticTypeToSetterInjectionRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const STATIC_TYPES = 'static_types';
    /**
     * @var string[]
     */
    private $staticTypes = [];
    /**
     * @var PropertyNaming
     */
    private $propertyNaming;
    /**
     * @var PhpDocTypeChanger
     */
    private $phpDocTypeChanger;
    /**
     * @param \Rector\Naming\Naming\PropertyNaming $propertyNaming
     * @param \Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger $phpDocTypeChanger
     */
    public function __construct($propertyNaming, $phpDocTypeChanger)
    {
        $this->propertyNaming = $propertyNaming;
        $this->phpDocTypeChanger = $phpDocTypeChanger;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        // custom made only for Elasticr
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes types to setter injection', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
<?php

namespace RectorPrefix20210317;

final class CheckoutEntityFactory
{
    public function run()
    {
        return \RectorPrefix20210317\SomeStaticClass::go();
    }
}
\class_alias('CheckoutEntityFactory', 'CheckoutEntityFactory', \false);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
<?php

namespace RectorPrefix20210317;

final class CheckoutEntityFactory
{
    /**
     * @var SomeStaticClass
     */
    private $someStaticClass;
    public function setSomeStaticClass(\RectorPrefix20210317\SomeStaticClass $someStaticClass)
    {
        $this->someStaticClass = $someStaticClass;
    }
    public function run()
    {
        return $this->someStaticClass->go();
    }
}
\class_alias('CheckoutEntityFactory', 'CheckoutEntityFactory', \false);
CODE_SAMPLE
, [self::STATIC_TYPES => ['SomeStaticClass']])]);
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
            return $this->processClass($node);
        }
        foreach ($this->staticTypes as $staticType) {
            $objectType = new \PHPStan\Type\ObjectType($staticType);
            if (!$this->isObjectType($node->class, $objectType)) {
                continue;
            }
            $variableName = $this->propertyNaming->fqnToVariableName($objectType);
            $propertyFetch = new \PhpParser\Node\Expr\PropertyFetch(new \PhpParser\Node\Expr\Variable('this'), $variableName);
            return new \PhpParser\Node\Expr\MethodCall($propertyFetch, $node->name, $node->args);
        }
        return null;
    }
    /**
     * @param mixed[] $configuration
     */
    public function configure($configuration) : void
    {
        $this->staticTypes = $configuration[self::STATIC_TYPES] ?? [];
    }
    /**
     * @param \PhpParser\Node\Stmt\Class_ $class
     */
    private function processClass($class) : \PhpParser\Node\Stmt\Class_
    {
        foreach ($this->staticTypes as $implements => $staticType) {
            $objectType = new \PHPStan\Type\ObjectType($staticType);
            $containsEntityFactoryStaticCall = (bool) $this->betterNodeFinder->findFirst($class->stmts, function (\PhpParser\Node $node) use($objectType) : bool {
                return $this->isEntityFactoryStaticCall($node, $objectType);
            });
            if (!$containsEntityFactoryStaticCall) {
                continue;
            }
            if (\is_string($implements)) {
                $class->implements[] = new \PhpParser\Node\Name\FullyQualified($implements);
            }
            $variableName = $this->propertyNaming->fqnToVariableName($objectType);
            $paramBuilder = new \RectorPrefix20210317\Symplify\Astral\ValueObject\NodeBuilder\ParamBuilder($variableName);
            $paramBuilder->setType(new \PhpParser\Node\Name\FullyQualified($staticType));
            $param = $paramBuilder->getNode();
            $assign = $this->nodeFactory->createPropertyAssignment($variableName);
            $setEntityFactoryMethod = $this->createSetEntityFactoryClassMethod($variableName, $param, $assign);
            $entityFactoryProperty = $this->nodeFactory->createPrivateProperty($variableName);
            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($entityFactoryProperty);
            $this->phpDocTypeChanger->changeVarType($phpDocInfo, $objectType);
            $class->stmts = \array_merge([$entityFactoryProperty, $setEntityFactoryMethod], $class->stmts);
            break;
        }
        return $class;
    }
    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Type\ObjectType $objectType
     */
    private function isEntityFactoryStaticCall($node, $objectType) : bool
    {
        if (!$node instanceof \PhpParser\Node\Expr\StaticCall) {
            return \false;
        }
        return $this->isObjectType($node->class, $objectType);
    }
    /**
     * @param string $variableName
     * @param \PhpParser\Node\Param $param
     * @param \PhpParser\Node\Expr\Assign $assign
     */
    private function createSetEntityFactoryClassMethod($variableName, $param, $assign) : \PhpParser\Node\Stmt\ClassMethod
    {
        $setMethodName = 'set' . \ucfirst($variableName);
        $setEntityFactoryMethodBuilder = new \RectorPrefix20210317\Symplify\Astral\ValueObject\NodeBuilder\MethodBuilder($setMethodName);
        $setEntityFactoryMethodBuilder->makePublic();
        $setEntityFactoryMethodBuilder->addParam($param);
        $setEntityFactoryMethodBuilder->setReturnType('void');
        $setEntityFactoryMethodBuilder->addStmt($assign);
        return $setEntityFactoryMethodBuilder->getNode();
    }
}
