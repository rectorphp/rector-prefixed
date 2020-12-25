<?php

declare (strict_types=1);
namespace Rector\Symfony5\Rector\New_;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BitwiseOr;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/symfony/symfony/blob/5.x/UPGRADE-5.2.md#propertyaccess
 * @see \Rector\Symfony5\Tests\Rector\New_\PropertyAccessorCreationBooleanToFlagsRector\PropertyAccessorCreationBooleanToFlagsRectorTest
 */
final class PropertyAccessorCreationBooleanToFlagsRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes first argument of PropertyAccessor::__construct() to flags from boolean', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $propertyAccessor = new PropertyAccessor(true);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $propertyAccessor = new PropertyAccessor(PropertyAccessor::MAGIC_CALL | PropertyAccessor::MAGIC_GET | PropertyAccessor::MAGIC_SET);
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
        return [\PhpParser\Node\Expr\New_::class];
    }
    /**
     * @param New_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $isTrue = $this->isTrue($node->args[0]->value);
        $bitwiseOr = $this->prepareFlags($isTrue);
        $node->args[0] = $this->createArg($bitwiseOr);
        return $node;
    }
    private function shouldSkip(\PhpParser\Node\Expr\New_ $new) : bool
    {
        if (!$new->class instanceof \PhpParser\Node\Name) {
            return \true;
        }
        if (!$this->isName($new->class, '_PhpScoper17db12703726\\Symfony\\Component\\PropertyAccess\\PropertyAccessor')) {
            return \true;
        }
        return !$this->isBool($new->args[0]->value);
    }
    private function prepareFlags(bool $currentValue) : \PhpParser\Node\Expr\BinaryOp\BitwiseOr
    {
        $classConstFetch = $this->createClassConstFetch('_PhpScoper17db12703726\\Symfony\\Component\\PropertyAccess\\PropertyAccessor', 'MAGIC_GET');
        $magicSet = $this->createClassConstFetch('_PhpScoper17db12703726\\Symfony\\Component\\PropertyAccess\\PropertyAccessor', 'MAGIC_SET');
        if (!$currentValue) {
            return new \PhpParser\Node\Expr\BinaryOp\BitwiseOr($classConstFetch, $magicSet);
        }
        return new \PhpParser\Node\Expr\BinaryOp\BitwiseOr(new \PhpParser\Node\Expr\BinaryOp\BitwiseOr($this->createClassConstFetch('_PhpScoper17db12703726\\Symfony\\Component\\PropertyAccess\\PropertyAccessor', 'MAGIC_CALL'), $classConstFetch), $magicSet);
    }
}