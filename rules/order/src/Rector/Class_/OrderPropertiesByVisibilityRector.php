<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Order\Rector\Class_;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Property;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Trait_;
use _PhpScopere8e811afab72\Rector\Order\Rector\AbstractConstantPropertyMethodOrderRector;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Order\Tests\Rector\Class_\OrderPropertiesByVisibilityRector\OrderPropertiesByVisibilityRectorTest
 */
final class OrderPropertiesByVisibilityRector extends \_PhpScopere8e811afab72\Rector\Order\Rector\AbstractConstantPropertyMethodOrderRector
{
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Orders properties by visibility', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    protected $protectedProperty;
    private $privateProperty;
    public $publicProperty;
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
    public $publicProperty;
    protected $protectedProperty;
    private $privateProperty;
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Trait_::class];
    }
    /**
     * @param Class_|Trait_ $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        $currentPropertiesOrder = $this->stmtOrder->getStmtsOfTypeOrder($node, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Property::class);
        $propertiesInDesiredOrder = $this->stmtVisibilitySorter->sortProperties($node);
        $oldToNewKeys = $this->stmtOrder->createOldToNewKeys($propertiesInDesiredOrder, $currentPropertiesOrder);
        // nothing to re-order
        if (!$this->hasOrderChanged($oldToNewKeys)) {
            return null;
        }
        return $this->stmtOrder->reorderClassStmtsByOldToNewKeys($node, $oldToNewKeys);
    }
}
