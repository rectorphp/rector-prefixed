<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Generic\Rector\Class_;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Name;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Trait_;
use _PhpScopere8e811afab72\Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator\ClassManipulator;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Generic\Tests\Rector\Class_\RemoveTraitRector\RemoveTraitRectorTest
 */
final class RemoveTraitRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector implements \_PhpScopere8e811afab72\Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const TRAITS_TO_REMOVE = '$traitsToRemove';
    /**
     * @var bool
     */
    private $classHasChanged = \false;
    /**
     * @var string[]
     */
    private $traitsToRemove = [];
    /**
     * @var ClassManipulator
     */
    private $classManipulator;
    public function __construct(\_PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator\ClassManipulator $classManipulator)
    {
        $this->classManipulator = $classManipulator;
    }
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove specific traits from code', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    use SomeTrait;
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
}
CODE_SAMPLE
, [self::TRAITS_TO_REMOVE => ['TraitNameToRemove']])]);
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
        $usedTraits = $this->classManipulator->getUsedTraits($node);
        if ($usedTraits === []) {
            return null;
        }
        $this->classHasChanged = \false;
        $this->removeTraits($usedTraits);
        // invoke re-print
        if ($this->classHasChanged) {
            $node->setAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE, null);
        }
        return $node;
    }
    public function configure(array $configuration) : void
    {
        $this->traitsToRemove = $configuration[self::TRAITS_TO_REMOVE] ?? [];
    }
    /**
     * @param Name[] $usedTraits
     */
    private function removeTraits(array $usedTraits) : void
    {
        foreach ($usedTraits as $usedTrait) {
            foreach ($this->traitsToRemove as $traitToRemove) {
                if ($this->isName($usedTrait, $traitToRemove)) {
                    $this->removeNode($usedTrait);
                    $this->classHasChanged = \true;
                    continue 2;
                }
            }
        }
    }
}
