<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\PHPUnit\Rector\Foreach_;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Arg;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall;
use _PhpScopere8e811afab72\PhpParser\Node\Name;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Foreach_;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator\ForeachManipulator;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\PHPUnit\Tests\Rector\Foreach_\SimplifyForeachInstanceOfRector\SimplifyForeachInstanceOfRectorTest
 */
final class SimplifyForeachInstanceOfRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var ForeachManipulator
     */
    private $foreachManipulator;
    public function __construct(\_PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator\ForeachManipulator $foreachManipulator)
    {
        $this->foreachManipulator = $foreachManipulator;
    }
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Simplify unnecessary foreach check of instances', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
foreach ($foos as $foo) {
    $this->assertInstanceOf(SplFileInfo::class, $foo);
}
CODE_SAMPLE
, '$this->assertContainsOnlyInstancesOf(\\SplFileInfo::class, $foos);')]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Foreach_::class];
    }
    /**
     * @param Foreach_ $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        /** @var MethodCall|StaticCall|null $matchedNode */
        $matchedNode = $this->foreachManipulator->matchOnlyStmt($node, function (\_PhpScopere8e811afab72\PhpParser\Node $node, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Foreach_ $foreachNode) : ?Node {
            if (!$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall && !$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall) {
                return null;
            }
            if (!$this->isName($node->name, 'assertInstanceOf')) {
                return null;
            }
            if (!$this->areNodesEqual($foreachNode->valueVar, $node->args[1]->value)) {
                return null;
            }
            return $node;
        });
        if ($matchedNode === null) {
            return null;
        }
        /** @var MethodCall|StaticCall $matchedNode */
        $callClass = \get_class($matchedNode);
        return new $callClass($this->resolveVar($matchedNode), new \_PhpScopere8e811afab72\PhpParser\Node\Name('assertContainsOnlyInstancesOf'), [$matchedNode->args[0], new \_PhpScopere8e811afab72\PhpParser\Node\Arg($node->expr)]);
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    private function resolveVar(\_PhpScopere8e811afab72\PhpParser\Node $node) : \_PhpScopere8e811afab72\PhpParser\Node
    {
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
            return $node->var;
        }
        return $node->class;
    }
}
