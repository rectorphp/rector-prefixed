<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Defluent\Rector\Return_;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\New_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_;
use _PhpScopere8e811afab72\Rector\Defluent\NodeFactory\ReturnFluentMethodCallFactory;
use _PhpScopere8e811afab72\Rector\Defluent\NodeFactory\SeparateReturnMethodCallFactory;
use _PhpScopere8e811afab72\Rector\Defluent\Rector\AbstractFluentChainMethodCallRector;
use _PhpScopere8e811afab72\Rector\Defluent\ValueObjectFactory\FluentMethodCallsFactory;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://ocramius.github.io/blog/fluent-interfaces-are-evil/
 * @see https://www.yegor256.com/2018/03/13/fluent-interfaces.html
 *
 * @see \Rector\Defluent\Tests\Rector\Return_\ReturnFluentChainMethodCallToNormalMethodCallRector\ReturnFluentChainMethodCallToNormalMethodCallRectorTest
 */
final class ReturnFluentChainMethodCallToNormalMethodCallRector extends \_PhpScopere8e811afab72\Rector\Defluent\Rector\AbstractFluentChainMethodCallRector
{
    /**
     * @var ReturnFluentMethodCallFactory
     */
    private $returnFluentMethodCallFactory;
    /**
     * @var FluentMethodCallsFactory
     */
    private $fluentMethodCallsFactory;
    /**
     * @var SeparateReturnMethodCallFactory
     */
    private $separateReturnMethodCallFactory;
    public function __construct(\_PhpScopere8e811afab72\Rector\Defluent\NodeFactory\ReturnFluentMethodCallFactory $returnFluentMethodCallFactory, \_PhpScopere8e811afab72\Rector\Defluent\ValueObjectFactory\FluentMethodCallsFactory $fluentMethodCallsFactory, \_PhpScopere8e811afab72\Rector\Defluent\NodeFactory\SeparateReturnMethodCallFactory $separateReturnMethodCallFactory)
    {
        $this->returnFluentMethodCallFactory = $returnFluentMethodCallFactory;
        $this->fluentMethodCallsFactory = $fluentMethodCallsFactory;
        $this->separateReturnMethodCallFactory = $separateReturnMethodCallFactory;
    }
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns fluent interface calls to classic ones.', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$someClass = new SomeClass();
return $someClass->someFunction()
            ->otherFunction();
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$someClass = new SomeClass();
$someClass->someFunction();
$someClass->otherFunction();
return $someClass;
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_::class];
    }
    /**
     * @param Return_ $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        $methodCall = $this->matchReturnMethodCall($node);
        if ($methodCall === null) {
            return null;
        }
        if ($this->shouldSkipMethodCallIncludingNew($methodCall)) {
            return null;
        }
        $nodesToAdd = $this->createStandaloneNodesToAddFromReturnFluentMethodCalls($methodCall);
        if ($nodesToAdd === []) {
            return null;
        }
        $this->removeCurrentNode($node);
        $this->addNodesAfterNode($nodesToAdd, $node);
        return null;
    }
    protected function shouldSkipMethodCallIncludingNew(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall) : bool
    {
        if ($this->shouldSkipMethodCall($methodCall)) {
            return \true;
        }
        $rootVariable = $this->fluentChainMethodCallNodeAnalyzer->resolveRootExpr($methodCall);
        return $rootVariable instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\New_;
    }
    /**
     * @return Node[]
     */
    private function createStandaloneNodesToAddFromReturnFluentMethodCalls(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall) : array
    {
        $fluentMethodCalls = $this->fluentMethodCallsFactory->createFromLastMethodCall($methodCall);
        if ($fluentMethodCalls === null) {
            return [];
        }
        $firstAssignFluentCall = $this->returnFluentMethodCallFactory->createFromFluentMethodCalls($fluentMethodCalls);
        if ($firstAssignFluentCall === null) {
            return [];
        }
        // should be skipped?
        if ($this->fluentMethodCallSkipper->shouldSkipFirstAssignFluentCall($firstAssignFluentCall)) {
            return [];
        }
        return $this->separateReturnMethodCallFactory->createReturnFromFirstAssignFluentCallAndFluentMethodCalls($firstAssignFluentCall, $fluentMethodCalls);
    }
}
