<?php

declare (strict_types=1);
namespace Rector\CodeQuality\Rector\Ternary;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Ternary;
use Rector\Core\Rector\AbstractRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://3v4l.org/f7itn
 *
 * @see \Rector\CodeQuality\Tests\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector\ArrayKeyExistsTernaryThenValueToCoalescingRectorTest
 */
final class ArrayKeyExistsTernaryThenValueToCoalescingRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change array_key_exists() ternary to coalesing', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run($values, $keyToMatch)
    {
        $result = array_key_exists($keyToMatch, $values) ? $values[$keyToMatch] : null;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($values, $keyToMatch)
    {
        $result = $values[$keyToMatch] ?? null;
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
        return [\PhpParser\Node\Expr\Ternary::class];
    }
    /**
     * @param Ternary $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$node->cond instanceof \PhpParser\Node\Expr\FuncCall) {
            return null;
        }
        if (!$this->isName($node->cond, 'array_key_exists')) {
            return null;
        }
        if (!$node->if instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            return null;
        }
        if (!$this->areArrayKeysExistsArgsMatchingDimFetch($node->cond, $node->if)) {
            return null;
        }
        if (!$this->isNull($node->else)) {
            return null;
        }
        return new \PhpParser\Node\Expr\BinaryOp\Coalesce($node->if, $node->else);
    }
    /**
     * Equals if:
     *
     * array_key_exists($key, $values);
     * =
     * $values[$key]
     */
    private function areArrayKeysExistsArgsMatchingDimFetch(\PhpParser\Node\Expr\FuncCall $funcCall, \PhpParser\Node\Expr\ArrayDimFetch $arrayDimFetch) : bool
    {
        $keyExpr = $funcCall->args[0]->value;
        $valuesExpr = $funcCall->args[1]->value;
        if (!$this->areNodesEqual($arrayDimFetch->var, $valuesExpr)) {
            return \false;
        }
        return $this->areNodesEqual($arrayDimFetch->dim, $keyExpr);
    }
}
