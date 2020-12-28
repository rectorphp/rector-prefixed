<?php

declare (strict_types=1);
namespace Rector\Php80\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\PhpParser\Node\Manipulator\ClassManipulator;
use Rector\Core\Rector\AbstractRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://wiki.php.net/rfc/stringable
 *
 * @see \Rector\Php80\Tests\Rector\Class_\StringableForToStringRector\StringableForToStringRectorTest
 */
final class StringableForToStringRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const STRINGABLE = 'Stringable';
    /**
     * @var ClassManipulator
     */
    private $classManipulator;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\ClassManipulator $classManipulator)
    {
        $this->classManipulator = $classManipulator;
    }
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Add `Stringable` interface to classes with `__toString()` method', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function __toString()
    {
        return 'I can stringz';
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass implements Stringable
{
    public function __toString(): string
    {
        return 'I can stringz';
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
        return [\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $toStringClassMethod = $node->getMethod('__toString');
        if ($toStringClassMethod === null) {
            return null;
        }
        if ($this->classManipulator->hasInterface($node, self::STRINGABLE)) {
            return null;
        }
        // add interface
        $node->implements[] = new \PhpParser\Node\Name\FullyQualified(self::STRINGABLE);
        // add return type
        if ($toStringClassMethod->returnType === null) {
            $toStringClassMethod->returnType = new \PhpParser\Node\Name('string');
        }
        return $node;
    }
}
