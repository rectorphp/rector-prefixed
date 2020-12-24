<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Php70\Rector\Assign;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayDimFetch;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\List_;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Rector\Core\ValueObject\PhpVersionFeature;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @source http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.variable-handling.list
 * @see \Rector\Php70\Tests\Rector\Assign\ListSwapArrayOrderRector\ListSwapArrayOrderRectorTest
 */
final class ListSwapArrayOrderRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('list() assigns variables in reverse order - relevant in array assign', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('list($a[], $a[]) = [1, 2];', 'list($a[], $a[]) = array_reverse([1, 2]);')]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign::class];
    }
    /**
     * @param Assign $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if (!$this->isAtLeastPhpVersion(\_PhpScoperb75b35f52b74\Rector\Core\ValueObject\PhpVersionFeature::LIST_SWAP_ORDER)) {
            return null;
        }
        if (!$node->var instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\List_) {
            return null;
        }
        $printerVars = [];
        foreach ($node->var->items as $arrayItem) {
            if (!$arrayItem instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem) {
                continue;
            }
            if ($arrayItem->value instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayDimFetch && $arrayItem->value->dim === null) {
                $printerVars[] = $this->print($arrayItem->value->var);
            } else {
                return null;
            }
        }
        // relevant only in 1 variable type
        if (\count(\array_unique($printerVars)) !== 1) {
            return null;
        }
        // wrap with array_reverse, to reflect reverse assign order in left
        $node->expr = $this->createFuncCall('array_reverse', [$node->expr]);
        return $node;
    }
}
