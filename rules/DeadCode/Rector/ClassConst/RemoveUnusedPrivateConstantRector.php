<?php

declare (strict_types=1);
namespace Rector\DeadCode\Rector\ClassConst;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use Rector\Core\NodeManipulator\ClassConstManipulator;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\DeadCode\Rector\ClassConst\RemoveUnusedPrivateConstantRector\RemoveUnusedPrivateConstantRectorTest
 */
final class RemoveUnusedPrivateConstantRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var ClassConstManipulator
     */
    private $classConstManipulator;
    /**
     * @param \Rector\Core\NodeManipulator\ClassConstManipulator $classConstManipulator
     */
    public function __construct($classConstManipulator)
    {
        $this->classConstManipulator = $classConstManipulator;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove unused private constant', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeController
{
    private const SOME_CONSTANT = 5;
    public function run()
    {
        return 5;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeController
{
    public function run()
    {
        return 5;
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\ClassConst::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (!$node->isPrivate()) {
            return null;
        }
        if (\count($node->consts) !== 1) {
            return null;
        }
        if ($this->classConstManipulator->hasClassConstFetch($node)) {
            return null;
        }
        // skip enum
        if ($this->classConstManipulator->isEnum($node)) {
            return null;
        }
        $this->removeNode($node);
        return $node;
    }
}
