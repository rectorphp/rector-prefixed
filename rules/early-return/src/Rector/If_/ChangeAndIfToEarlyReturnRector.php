<?php

declare (strict_types=1);
namespace Rector\EarlyReturn\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\While_;
use Rector\Core\NodeManipulator\IfManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\EarlyReturn\NodeTransformer\ConditionInverter;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\EarlyReturn\Tests\Rector\If_\ChangeAndIfToEarlyReturnRector\ChangeAndIfToEarlyReturnRectorTest
 */
final class ChangeAndIfToEarlyReturnRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var array<class-string<Stmt>>
     */
    public const LOOP_TYPES = [\PhpParser\Node\Stmt\Foreach_::class, \PhpParser\Node\Stmt\For_::class, \PhpParser\Node\Stmt\While_::class];
    /**
     * @var IfManipulator
     */
    private $ifManipulator;
    /**
     * @var ConditionInverter
     */
    private $conditionInverter;
    public function __construct(\Rector\EarlyReturn\NodeTransformer\ConditionInverter $conditionInverter, \Rector\Core\NodeManipulator\IfManipulator $ifManipulator)
    {
        $this->ifManipulator = $ifManipulator;
        $this->conditionInverter = $conditionInverter;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes if && to early return', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function canDrive(Car $car)
    {
        if ($car->hasWheels && $car->hasFuel) {
            return true;
        }

        return false;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function canDrive(Car $car)
    {
        if (!$car->hasWheels) {
            return false;
        }

        if (!$car->hasFuel) {
            return false;
        }

        return true;
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
        return [\PhpParser\Node\Stmt\If_::class];
    }
    /**
     * @param If_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $ifNextReturn = $this->getIfNextReturn($node);
        if ($ifNextReturn instanceof \PhpParser\Node\Stmt\Return_ && $this->isIfStmtExprUsedInNextReturn($node, $ifNextReturn)) {
            return null;
        }
        /** @var BooleanAnd $expr */
        $expr = $node->cond;
        $conditions = $this->getBooleanAndConditions($expr);
        $ifNextReturnClone = $ifNextReturn instanceof \PhpParser\Node\Stmt\Return_ ? clone $ifNextReturn : new \PhpParser\Node\Stmt\Return_();
        $isInLoop = $this->isIfInLoop($node);
        if (!$ifNextReturn instanceof \PhpParser\Node\Stmt\Return_) {
            $this->addNodeAfterNode($node->stmts[0], $node);
            return $this->processReplaceIfs($node, $conditions, $ifNextReturnClone);
        }
        $this->removeNode($ifNextReturn);
        $ifNextReturn = $node->stmts[0];
        $this->addNodeAfterNode($ifNextReturn, $node);
        if (!$isInLoop) {
            return $this->processReplaceIfs($node, $conditions, $ifNextReturnClone);
        }
        if (\property_exists($ifNextReturn, 'expr') && $ifNextReturn->expr instanceof \PhpParser\Node\Expr) {
            $this->addNodeAfterNode(new \PhpParser\Node\Stmt\Return_(), $node);
        }
        return $this->processReplaceIfs($node, $conditions, $ifNextReturnClone);
    }
    /**
     * @param Expr[] $conditions
     */
    private function processReplaceIfs(\PhpParser\Node\Stmt\If_ $node, array $conditions, \PhpParser\Node\Stmt\Return_ $ifNextReturnClone) : \PhpParser\Node\Stmt\If_
    {
        $ifs = $this->createInvertedIfNodesFromConditions($node, $conditions, $ifNextReturnClone);
        $this->mirrorComments($ifs[0], $node);
        foreach ($ifs as $if) {
            $this->addNodeBeforeNode($if, $node);
        }
        $this->removeNode($node);
        return $node;
    }
    private function shouldSkip(\PhpParser\Node\Stmt\If_ $if) : bool
    {
        if (!$this->ifManipulator->isIfWithOnlyOneStmt($if)) {
            return \true;
        }
        if (!$if->cond instanceof \PhpParser\Node\Expr\BinaryOp\BooleanAnd) {
            return \true;
        }
        if (!$this->ifManipulator->isIfWithoutElseAndElseIfs($if)) {
            return \true;
        }
        if ($this->isParentIfReturnsVoidOrParentIfHasNextNode($if)) {
            return \true;
        }
        if ($this->isNestedIfInLoop($if)) {
            return \true;
        }
        return !$this->isLastIfOrBeforeLastReturn($if);
    }
    /**
     * @return Expr[]
     */
    private function getBooleanAndConditions(\PhpParser\Node\Expr\BinaryOp\BooleanAnd $booleanAnd) : array
    {
        $ifs = [];
        while (\property_exists($booleanAnd, 'left')) {
            $ifs[] = $booleanAnd->right;
            $booleanAnd = $booleanAnd->left;
            if (!$booleanAnd instanceof \PhpParser\Node\Expr\BinaryOp\BooleanAnd) {
                $ifs[] = $booleanAnd;
                break;
            }
        }
        \krsort($ifs);
        return $ifs;
    }
    private function isIfStmtExprUsedInNextReturn(\PhpParser\Node\Stmt\If_ $if, \PhpParser\Node\Stmt\Return_ $return) : bool
    {
        if (!$return->expr instanceof \PhpParser\Node\Expr) {
            return \false;
        }
        $ifExprs = $this->betterNodeFinder->findInstanceOf($if->stmts, \PhpParser\Node\Expr::class);
        foreach ($ifExprs as $expr) {
            $isExprFoundInReturn = (bool) $this->betterNodeFinder->findFirst($return->expr, function (\PhpParser\Node $node) use($expr) : bool {
                return $this->areNodesEqual($node, $expr);
            });
            if ($isExprFoundInReturn) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @param Expr[] $conditions
     * @return If_[]
     */
    private function createInvertedIfNodesFromConditions(\PhpParser\Node\Stmt\If_ $if, array $conditions, \PhpParser\Node\Stmt\Return_ $return) : array
    {
        $ifs = [];
        $stmt = $this->isIfInLoop($if) && !$this->getIfNextReturn($if) ? [new \PhpParser\Node\Stmt\Continue_()] : [$return];
        foreach ($conditions as $condition) {
            $invertedCondition = $this->conditionInverter->createInvertedCondition($condition);
            $if = new \PhpParser\Node\Stmt\If_($invertedCondition);
            $if->stmts = $stmt;
            $ifs[] = $if;
        }
        return $ifs;
    }
    private function getIfNextReturn(\PhpParser\Node\Stmt\If_ $if) : ?\PhpParser\Node\Stmt\Return_
    {
        $nextNode = $if->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        if (!$nextNode instanceof \PhpParser\Node\Stmt\Return_) {
            return null;
        }
        return $nextNode;
    }
    private function isIfInLoop(\PhpParser\Node\Stmt\If_ $if) : bool
    {
        $parentLoop = $this->betterNodeFinder->findParentTypes($if, self::LOOP_TYPES);
        return $parentLoop !== null;
    }
    private function isParentIfReturnsVoidOrParentIfHasNextNode(\PhpParser\Node\Stmt\If_ $if) : bool
    {
        $parentNode = $if->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$parentNode instanceof \PhpParser\Node\Stmt\If_) {
            return \false;
        }
        $nextParent = $parentNode->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        return $nextParent instanceof \PhpParser\Node;
    }
    private function isNestedIfInLoop(\PhpParser\Node\Stmt\If_ $if) : bool
    {
        if (!$this->isIfInLoop($if)) {
            return \false;
        }
        return (bool) $this->betterNodeFinder->findParentTypes($if, [\PhpParser\Node\Stmt\If_::class, \PhpParser\Node\Stmt\Else_::class, \PhpParser\Node\Stmt\ElseIf_::class]);
    }
    private function isLastIfOrBeforeLastReturn(\PhpParser\Node\Stmt\If_ $if) : bool
    {
        $nextNode = $if->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        if ($nextNode instanceof \PhpParser\Node) {
            return $nextNode instanceof \PhpParser\Node\Stmt\Return_;
        }
        $parent = $if->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parent instanceof \PhpParser\Node\Stmt\If_) {
            return $this->isLastIfOrBeforeLastReturn($parent);
        }
        return \true;
    }
}
