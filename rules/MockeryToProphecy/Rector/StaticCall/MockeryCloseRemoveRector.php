<?php

declare (strict_types=1);
namespace Rector\MockeryToProphecy\Rector\StaticCall;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\PHPUnit\NodeAnalyzer\TestsNodeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\MockeryToProphecy\Rector\StaticCall\MockeryToProphecyRector\MockeryToProphecyRectorTest
 */
final class MockeryCloseRemoveRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var TestsNodeAnalyzer
     */
    private $testsNodeAnalyzer;
    /**
     * @param \Rector\PHPUnit\NodeAnalyzer\TestsNodeAnalyzer $testsNodeAnalyzer
     */
    public function __construct($testsNodeAnalyzer)
    {
        $this->testsNodeAnalyzer = $testsNodeAnalyzer;
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (!$this->testsNodeAnalyzer->isInTestClass($node)) {
            return null;
        }
        if (!$this->nodeNameResolver->isStaticCallNamed($node, 'Mockery', 'close')) {
            return null;
        }
        $this->removeNode($node);
        return null;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Removes mockery close from test classes', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
public function tearDown() : void
{
    \Mockery::close();
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
public function tearDown() : void
{
}
CODE_SAMPLE
)]);
    }
}
