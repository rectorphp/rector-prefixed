<?php

declare (strict_types=1);
namespace Rector\Order\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\PhpParser\Node\Manipulator\ClassManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Order\StmtOrder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Order\Tests\Rector\Class_\OrderPublicInterfaceMethodRector\OrderPublicInterfaceMethodRectorTest
 */
final class OrderPublicInterfaceMethodRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const METHOD_ORDER_BY_INTERFACES = 'method_order_by_interfaces';
    /**
     * @var string[][]
     */
    private $methodOrderByInterfaces = [];
    /**
     * @var ClassManipulator
     */
    private $classManipulator;
    /**
     * @var StmtOrder
     */
    private $stmtOrder;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\ClassManipulator $classManipulator, \Rector\Order\StmtOrder $stmtOrder)
    {
        $this->classManipulator = $classManipulator;
        $this->stmtOrder = $stmtOrder;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Order public methods required by interface in custom orderer', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeClass implements FoodRecipeInterface
{
    public function process()
    {
    }

    public function getDescription()
    {
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass implements FoodRecipeInterface
{
    public function getDescription()
    {
    }
    public function process()
    {
    }
}
CODE_SAMPLE
, [self::METHOD_ORDER_BY_INTERFACES => ['FoodRecipeInterface' => ['getDescription', 'process']]])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $implementedInterfaces = $this->classManipulator->getImplementedInterfaceNames($node);
        if ($implementedInterfaces === []) {
            return null;
        }
        $publicMethodOrderByKey = $this->collectPublicMethods($node);
        foreach ($implementedInterfaces as $implementedInterface) {
            $methodOrder = $this->methodOrderByInterfaces[$implementedInterface] ?? null;
            if ($methodOrder === null) {
                continue;
            }
            $oldToNewKeys = $this->stmtOrder->createOldToNewKeys($publicMethodOrderByKey, $methodOrder);
            // nothing to re-order
            if (\array_keys($oldToNewKeys) === \array_values($oldToNewKeys)) {
                return null;
            }
            $this->stmtOrder->reorderClassStmtsByOldToNewKeys($node, $oldToNewKeys);
            break;
        }
        return $node;
    }
    public function configure(array $configuration) : void
    {
        $this->methodOrderByInterfaces = $configuration[self::METHOD_ORDER_BY_INTERFACES] ?? [];
    }
    /**
     * @return string[]
     */
    private function collectPublicMethods(\PhpParser\Node\Stmt\Class_ $class) : array
    {
        $publicClassMethods = [];
        foreach ($class->stmts as $key => $classStmt) {
            if (!$classStmt instanceof \PhpParser\Node\Stmt\ClassMethod) {
                continue;
            }
            if (!$classStmt->isPublic()) {
                continue;
            }
            /** @var string $classMethodName */
            $classMethodName = $this->getName($classStmt);
            $publicClassMethods[$key] = $classMethodName;
        }
        return $publicClassMethods;
    }
}
