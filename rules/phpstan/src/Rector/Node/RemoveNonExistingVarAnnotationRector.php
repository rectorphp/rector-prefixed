<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\PHPStan\Rector\Node;

use _PhpScopere8e811afab72\Nette\Utils\Strings;
use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Assign;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\AssignRef;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Echo_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Foreach_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\If_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Nop;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Static_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Switch_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Throw_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\While_;
use _PhpScopere8e811afab72\PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use _PhpScopere8e811afab72\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\PHPStan\Tests\Rector\Node\RemoveNonExistingVarAnnotationRector\RemoveNonExistingVarAnnotationRectorTest
 *
 * @see https://github.com/phpstan/phpstan/commit/d17e459fd9b45129c5deafe12bca56f30ea5ee99#diff-9f3541876405623b0d18631259763dc1
 */
final class RemoveNonExistingVarAnnotationRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var class-string[]
     */
    private const NODES_TO_MATCH = [\_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign::class, \_PhpScopere8e811afab72\PhpParser\Node\Expr\AssignRef::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Foreach_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Static_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Echo_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Throw_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\While_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Switch_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Nop::class];
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Removes non-existing @var annotations above the code', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function get()
    {
        /** @var Training[] $trainings */
        return $this->getData();
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function get()
    {
        return $this->getData();
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
        return [\_PhpScopere8e811afab72\PhpParser\Node::class];
    }
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $node->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return null;
        }
        $attributeAwareVarTagValueNode = $phpDocInfo->getVarTagValueNode();
        if ($attributeAwareVarTagValueNode === null) {
            return null;
        }
        $variableName = $attributeAwareVarTagValueNode->variableName;
        if ($variableName === '') {
            return null;
        }
        $nodeContentWithoutPhpDoc = $this->printWithoutComments($node);
        // it's there
        if (\_PhpScopere8e811afab72\Nette\Utils\Strings::match($nodeContentWithoutPhpDoc, '#' . \preg_quote($variableName, '#') . '\\b#')) {
            return null;
        }
        $phpDocInfo->removeByType(\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode::class);
        return $node;
    }
    private function shouldSkip(\_PhpScopere8e811afab72\PhpParser\Node $node) : bool
    {
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Nop && \count($node->getComments()) > 1) {
            return \true;
        }
        foreach (self::NODES_TO_MATCH as $nodeToMatch) {
            if (!\is_a($node, $nodeToMatch, \true)) {
                continue;
            }
            return \false;
        }
        return \true;
    }
}
