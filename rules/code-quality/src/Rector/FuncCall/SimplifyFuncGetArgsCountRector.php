<?php

declare (strict_types=1);
namespace Rector\CodeQuality\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Rector\AbstractRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\CodeQuality\Tests\Rector\FuncCall\SimplifyFuncGetArgsCountRector\SimplifyFuncGetArgsCountRectorTest
 */
final class SimplifyFuncGetArgsCountRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Simplify count of func_get_args() to func_num_args()', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('count(func_get_args());', 'func_num_args();')]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param FuncCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isName($node, 'count')) {
            return null;
        }
        if (!$node->args[0]->value instanceof \PhpParser\Node\Expr\FuncCall) {
            return null;
        }
        /** @var FuncCall $innerFuncCall */
        $innerFuncCall = $node->args[0]->value;
        if (!$this->isName($innerFuncCall, 'func_get_args')) {
            return $node;
        }
        return $this->createFuncCall('func_num_args');
    }
}
