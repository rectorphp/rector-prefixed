<?php

declare (strict_types=1);
namespace Rector\CodeQuality\Rector\Identical;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Core\PhpParser\Node\Manipulator\BinaryOpManipulator;
use Rector\Core\Rector\AbstractRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\CodeQuality\Tests\Rector\Identical\GetClassToInstanceOfRector\GetClassToInstanceOfRectorTest
 */
final class GetClassToInstanceOfRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var BinaryOpManipulator
     */
    private $binaryOpManipulator;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\BinaryOpManipulator $binaryOpManipulator)
    {
        $this->binaryOpManipulator = $binaryOpManipulator;
    }
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes comparison with get_class to instanceof', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('if (EventsListener::class === get_class($event->job)) { }', 'if ($event->job instanceof EventsListener) { }')]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\BinaryOp\Identical::class, \PhpParser\Node\Expr\BinaryOp\NotIdentical::class];
    }
    /**
     * @param Identical|NotIdentical $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $twoNodeMatch = $this->binaryOpManipulator->matchFirstAndSecondConditionNode($node, function (\PhpParser\Node $node) : bool {
            return $this->isClassReference($node);
        }, function (\PhpParser\Node $node) : bool {
            return $this->isGetClassFuncCallNode($node);
        });
        if ($twoNodeMatch === null) {
            return null;
        }
        /** @var ClassConstFetch|String_ $firstExpr */
        $firstExpr = $twoNodeMatch->getFirstExpr();
        /** @var FuncCall $funcCall */
        $funcCall = $twoNodeMatch->getSecondExpr();
        $varNode = $funcCall->args[0]->value;
        if ($firstExpr instanceof \PhpParser\Node\Scalar\String_) {
            $className = $this->getValue($firstExpr);
        } else {
            $className = $this->getName($firstExpr->class);
        }
        if ($className === null) {
            return null;
        }
        $instanceof = new \PhpParser\Node\Expr\Instanceof_($varNode, new \PhpParser\Node\Name\FullyQualified($className));
        if ($node instanceof \PhpParser\Node\Expr\BinaryOp\NotIdentical) {
            return new \PhpParser\Node\Expr\BooleanNot($instanceof);
        }
        return $instanceof;
    }
    private function isClassReference(\PhpParser\Node $node) : bool
    {
        if ($node instanceof \PhpParser\Node\Expr\ClassConstFetch && $this->isName($node->name, 'class')) {
            return \true;
        }
        // might be
        return $node instanceof \PhpParser\Node\Scalar\String_;
    }
    private function isGetClassFuncCallNode(\PhpParser\Node $node) : bool
    {
        if (!$node instanceof \PhpParser\Node\Expr\FuncCall) {
            return \false;
        }
        return $this->isName($node, 'get_class');
    }
}
