<?php

declare (strict_types=1);
namespace Rector\CodeQuality\Rector\BooleanAnd;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\Empty_;
use Rector\Core\NodeManipulator\BinaryOpManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Php71\ValueObject\TwoNodeMatch;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://3v4l.org/EZ2P4
 * @see https://3v4l.org/egtb5
 * @see \Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\SimplifyEmptyArrayCheckRectorTest
 */
final class SimplifyEmptyArrayCheckRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var BinaryOpManipulator
     */
    private $binaryOpManipulator;
    /**
     * @param \Rector\Core\NodeManipulator\BinaryOpManipulator $binaryOpManipulator
     */
    public function __construct($binaryOpManipulator)
    {
        $this->binaryOpManipulator = $binaryOpManipulator;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Simplify `is_array` and `empty` functions combination into a simple identical check for an empty array', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('is_array($values) && empty($values)', '$values === []')]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\BinaryOp\BooleanAnd::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        $twoNodeMatch = $this->binaryOpManipulator->matchFirstAndSecondConditionNode(
            $node,
            // is_array(...)
            function (\PhpParser\Node $node) : bool {
                return $this->nodeNameResolver->isFuncCallName($node, 'is_array');
            },
            \PhpParser\Node\Expr\Empty_::class
        );
        if (!$twoNodeMatch instanceof \Rector\Php71\ValueObject\TwoNodeMatch) {
            return null;
        }
        /** @var Empty_ $emptyOrNotIdenticalNode */
        $emptyOrNotIdenticalNode = $twoNodeMatch->getSecondExpr();
        return new \PhpParser\Node\Expr\BinaryOp\Identical($emptyOrNotIdenticalNode->expr, new \PhpParser\Node\Expr\Array_());
    }
}
