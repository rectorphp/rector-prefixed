<?php

declare (strict_types=1);
namespace Rector\TestPackageName\Rector\Arg;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\TestPackageName\Tests\Rector\Arg\TestRector\TestRectorTest
 */
final class TestRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Description', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'PHP'
class SomeClass
{
    public function run()
    {
        $this->something();
    }
}
PHP
, <<<'PHP'
class SomeClass
{
    public function run()
    {
        $this->somethingElse();
    }
}
PHP
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Arg::class];
    }
    /**
     * @param \PhpParser\Node\Arg $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        // change the node
        return $node;
    }
}