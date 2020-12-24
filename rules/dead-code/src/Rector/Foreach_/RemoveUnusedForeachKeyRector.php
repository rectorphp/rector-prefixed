<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Foreach_;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Foreach_;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DeadCode\Tests\Rector\Foreach_\RemoveUnusedForeachKeyRector\RemoveUnusedForeachKeyRectorTest
 */
final class RemoveUnusedForeachKeyRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove unused key in foreach', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$items = [];
foreach ($items as $key => $value) {
    $result = $value;
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$items = [];
foreach ($items as $value) {
    $result = $value;
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Foreach_::class];
    }
    /**
     * @param Foreach_ $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if ($node->keyVar === null) {
            return null;
        }
        $keyVar = $node->keyVar;
        $isNodeUsed = (bool) $this->betterNodeFinder->findFirst($node->stmts, function (\_PhpScoperb75b35f52b74\PhpParser\Node $node) use($keyVar) : bool {
            return $this->areNodesEqual($node, $keyVar);
        });
        if ($isNodeUsed) {
            return null;
        }
        $node->keyVar = null;
        return $node;
    }
}
