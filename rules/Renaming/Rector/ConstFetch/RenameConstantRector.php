<?php

declare (strict_types=1);
namespace Rector\Renaming\Rector\ConstFetch;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\Renaming\Rector\ConstFetch\RenameConstantRector\RenameConstantRectorTest
 */
final class RenameConstantRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const OLD_TO_NEW_CONSTANTS = '$oldToNewConstants';
    /**
     * @var string[]
     */
    private $oldToNewConstants = [];
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Replace constant by new ones', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        return MYSQL_ASSOC;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        return MYSQLI_ASSOC;
    }
}
CODE_SAMPLE
, [self::OLD_TO_NEW_CONSTANTS => ['MYSQL_ASSOC' => 'MYSQLI_ASSOC', 'OLD_CONSTANT' => 'NEW_CONSTANT']])]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\ConstFetch::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        foreach ($this->oldToNewConstants as $oldConstant => $newConstant) {
            if (!$this->isName($node, $oldConstant)) {
                continue;
            }
            $node->name = new \PhpParser\Node\Name($newConstant);
            break;
        }
        return $node;
    }
    /**
     * @param mixed[] $configuration
     */
    public function configure($configuration) : void
    {
        $this->oldToNewConstants = $configuration[self::OLD_TO_NEW_CONSTANTS] ?? [];
    }
}
