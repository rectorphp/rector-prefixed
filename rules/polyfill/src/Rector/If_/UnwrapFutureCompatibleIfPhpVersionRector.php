<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Polyfill\Rector\If_;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\If_;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\Polyfill\ConditionEvaluator;
use _PhpScopere8e811afab72\Rector\Polyfill\ConditionResolver;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://www.php.net/manual/en/function.version-compare.php
 *
 * @see \Rector\Polyfill\Tests\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector\UnwrapFutureCompatibleIfPhpVersionRectorTest
 */
final class UnwrapFutureCompatibleIfPhpVersionRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var ConditionEvaluator
     */
    private $conditionEvaluator;
    /**
     * @var ConditionResolver
     */
    private $conditionResolver;
    public function __construct(\_PhpScopere8e811afab72\Rector\Polyfill\ConditionEvaluator $conditionEvaluator, \_PhpScopere8e811afab72\Rector\Polyfill\ConditionResolver $conditionResolver)
    {
        $this->conditionEvaluator = $conditionEvaluator;
        $this->conditionResolver = $conditionResolver;
    }
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove php version checks if they are passed', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
// current PHP: 7.2
if (version_compare(PHP_VERSION, '7.2', '<')) {
    return 'is PHP 7.1-';
} else {
    return 'is PHP 7.2+';
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
// current PHP: 7.2
return 'is PHP 7.2+';
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_::class];
    }
    /**
     * @param If_ $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if ((bool) $node->elseifs) {
            return null;
        }
        $condition = $this->conditionResolver->resolveFromExpr($node->cond);
        if ($condition === null) {
            return null;
        }
        $result = $this->conditionEvaluator->evaluate($condition);
        if ($result === null) {
            return null;
        }
        // if is skipped
        if ($result) {
            $this->refactorIsMatch($node);
        } else {
            $this->refactorIsNotMatch($node);
        }
        return $node;
    }
    private function refactorIsMatch(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : void
    {
        if ((bool) $if->elseifs) {
            return;
        }
        $this->unwrapStmts($if->stmts, $if);
        $this->removeNode($if);
    }
    private function refactorIsNotMatch(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : void
    {
        // no else → just remove the node
        if ($if->else === null) {
            $this->removeNode($if);
            return;
        }
        // else is always used
        $this->unwrapStmts($if->else->stmts, $if);
        $this->removeNode($if);
    }
}
