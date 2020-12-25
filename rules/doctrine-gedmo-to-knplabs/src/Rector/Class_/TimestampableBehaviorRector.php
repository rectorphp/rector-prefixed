<?php

declare (strict_types=1);
namespace Rector\DoctrineGedmoToKnplabs\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\PhpParser\Node\Manipulator\ClassManipulator;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/Atlantic18/DoctrineExtensions/blob/v2.4.x/doc/timestampable.md
 * @see https://github.com/KnpLabs/DoctrineBehaviors/blob/4e0677379dd4adf84178f662d08454a9627781a8/docs/timestampable.md
 *
 * @see \Rector\DoctrineGedmoToKnplabs\Tests\Rector\Class_\TimestampableBehaviorRector\TimestampableBehaviorRectorTest
 */
final class TimestampableBehaviorRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var ClassManipulator
     */
    private $classManipulator;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\ClassManipulator $classManipulator)
    {
        $this->classManipulator = $classManipulator;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change Timestampable from gedmo/doctrine-extensions to knplabs/doctrine-behaviors', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Gedmo\Timestampable\Traits\TimestampableEntity;

class SomeClass
{
    use TimestampableEntity;
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;

class SomeClass implements TimestampableInterface
{
    use TimestampableTrait;
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->classManipulator->hasTrait($node, '_PhpScoper5b8c9e9ebd21\\Gedmo\\Timestampable\\Traits\\TimestampableEntity')) {
            return null;
        }
        $this->classManipulator->replaceTrait($node, '_PhpScoper5b8c9e9ebd21\\Gedmo\\Timestampable\\Traits\\TimestampableEntity', '_PhpScoper5b8c9e9ebd21\\Knp\\DoctrineBehaviors\\Model\\Timestampable\\TimestampableTrait');
        $node->implements[] = new \PhpParser\Node\Name\FullyQualified('_PhpScoper5b8c9e9ebd21\\Knp\\DoctrineBehaviors\\Contract\\Entity\\TimestampableInterface');
        return $node;
    }
}
