<?php

declare (strict_types=1);
namespace Rector\EarlyReturn\NodeTransformer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BooleanNot;
use Rector\Core\PhpParser\Node\Manipulator\BinaryOpManipulator;
final class ConditionInverter
{
    /**
     * @var BinaryOpManipulator
     */
    private $binaryOpManipulator;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\BinaryOpManipulator $binaryOpManipulator)
    {
        $this->binaryOpManipulator = $binaryOpManipulator;
    }
    public function createInvertedCondition(\PhpParser\Node\Expr $expr) : \PhpParser\Node\Expr
    {
        // inverse condition
        if ($expr instanceof \PhpParser\Node\Expr\BinaryOp) {
            $inversedCondition = $this->binaryOpManipulator->invertCondition($expr);
            if ($inversedCondition === null) {
                return new \PhpParser\Node\Expr\BooleanNot($expr);
            }
            if ($inversedCondition instanceof \PhpParser\Node\Expr\BinaryOp\BooleanAnd) {
                return new \PhpParser\Node\Expr\BooleanNot($expr);
            }
            return $inversedCondition;
        }
        if ($expr instanceof \PhpParser\Node\Expr\BooleanNot) {
            return $expr->expr;
        }
        return new \PhpParser\Node\Expr\BooleanNot($expr);
    }
}