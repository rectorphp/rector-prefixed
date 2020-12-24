<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Array_;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayItem;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://3v4l.org/aLf96
 * @see https://3v4l.org/2r26K
 * @see https://3v4l.org/anks3
 *
 * @see \Rector\CodeQuality\Tests\Rector\FuncCall\ArrayMergeOfNonArraysToSimpleArrayRector\ArrayMergeOfNonArraysToSimpleArrayRectorTest
 */
final class ArrayMergeOfNonArraysToSimpleArrayRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change array_merge of non arrays to array directly', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function go()
    {
        $value = 5;
        $value2 = 10;

        return array_merge([$value], [$value2]);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function go()
    {
        $value = 5;
        $value2 = 10;

        return [$value, $value2];
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
        return [\_PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param FuncCall $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if (!$this->isName($node, 'array_merge')) {
            return null;
        }
        $array = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Array_();
        foreach ($node->args as $arg) {
            /** @var Array_ $nestedArrayItem */
            $nestedArrayItem = $arg->value;
            // skip
            if (!$nestedArrayItem instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Array_) {
                return null;
            }
            foreach ($nestedArrayItem->items as $nestedArrayItemItem) {
                if ($nestedArrayItemItem === null) {
                    continue;
                }
                $array->items[] = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayItem($nestedArrayItemItem->value, $nestedArrayItemItem->key);
            }
        }
        return $array;
    }
}
