<?php

declare (strict_types=1);
namespace Rector\Php71\Rector\List_;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Stmt\Foreach_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://wiki.php.net/rfc/short_list_syntax
 * @see https://www.php.net/manual/en/migration71.new-features.php#migration71.new-features.symmetric-array-destructuring
 */
/**
 * @see \Rector\Php71\Tests\Rector\List_\ListToArrayDestructRector\ListToArrayDestructRectorTest
 */
final class ListToArrayDestructRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove & from new &X', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        list($id1, $name1) = $data;

        foreach ($data as list($id, $name)) {
        }
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        [$id1, $name1] = $data;

        foreach ($data as [$id, $name]) {
        }
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
        return [\PhpParser\Node\Expr\List_::class];
    }
    /**
     * @param List_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isAtLeastPhpVersion(\Rector\Core\ValueObject\PhpVersionFeature::ARRAY_DESTRUCT)) {
            return null;
        }
        $parentNode = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parentNode instanceof \PhpParser\Node\Expr\Assign && $parentNode->var === $node) {
            return new \PhpParser\Node\Expr\Array_($node->items);
        }
        if ($parentNode instanceof \PhpParser\Node\Stmt\Foreach_ && $parentNode->valueVar === $node) {
            return new \PhpParser\Node\Expr\Array_($node->items);
        }
        return null;
    }
}
