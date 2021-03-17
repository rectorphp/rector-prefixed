<?php

declare (strict_types=1);
namespace Rector\Defluent\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use Rector\Core\Rector\AbstractRector;
use Rector\Defluent\Matcher\AssignAndRootExprAndNodesToAddMatcher;
use Rector\Defluent\NodeAnalyzer\FluentChainMethodCallNodeAnalyzer;
use Rector\Defluent\NodeAnalyzer\NewFluentChainMethodCallNodeAnalyzer;
use Rector\Defluent\NodeFactory\FluentMethodCallAsArgFactory;
use Rector\Defluent\NodeFactory\NonFluentChainMethodCallFactory;
use Rector\Defluent\NodeFactory\VariableFromNewFactory;
use Rector\Defluent\ValueObject\AssignAndRootExprAndNodesToAdd;
use Rector\Defluent\ValueObject\FluentCallsKind;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\Defluent\Rector\MethodCall\InArgChainFluentMethodCallToStandaloneMethodCallRectorTest\InArgChainFluentMethodCallToStandaloneMethodCallRectorTest
 */
final class InArgFluentChainMethodCallToStandaloneMethodCallRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var NewFluentChainMethodCallNodeAnalyzer
     */
    private $newFluentChainMethodCallNodeAnalyzer;
    /**
     * @var VariableFromNewFactory
     */
    private $variableFromNewFactory;
    /**
     * @var FluentMethodCallAsArgFactory
     */
    private $fluentMethodCallAsArgFactory;
    /**
     * @var AssignAndRootExprAndNodesToAddMatcher
     */
    private $assignAndRootExprAndNodesToAddMatcher;
    /**
     * @var FluentChainMethodCallNodeAnalyzer
     */
    private $fluentChainMethodCallNodeAnalyzer;
    /**
     * @var NonFluentChainMethodCallFactory
     */
    private $nonFluentChainMethodCallFactory;
    /**
     * @param \Rector\Defluent\NodeAnalyzer\NewFluentChainMethodCallNodeAnalyzer $newFluentChainMethodCallNodeAnalyzer
     * @param \Rector\Defluent\NodeFactory\VariableFromNewFactory $variableFromNewFactory
     * @param \Rector\Defluent\NodeFactory\FluentMethodCallAsArgFactory $fluentMethodCallAsArgFactory
     * @param \Rector\Defluent\Matcher\AssignAndRootExprAndNodesToAddMatcher $assignAndRootExprAndNodesToAddMatcher
     * @param \Rector\Defluent\NodeAnalyzer\FluentChainMethodCallNodeAnalyzer $fluentChainMethodCallNodeAnalyzer
     * @param \Rector\Defluent\NodeFactory\NonFluentChainMethodCallFactory $nonFluentChainMethodCallFactory
     */
    public function __construct($newFluentChainMethodCallNodeAnalyzer, $variableFromNewFactory, $fluentMethodCallAsArgFactory, $assignAndRootExprAndNodesToAddMatcher, $fluentChainMethodCallNodeAnalyzer, $nonFluentChainMethodCallFactory)
    {
        $this->newFluentChainMethodCallNodeAnalyzer = $newFluentChainMethodCallNodeAnalyzer;
        $this->variableFromNewFactory = $variableFromNewFactory;
        $this->fluentMethodCallAsArgFactory = $fluentMethodCallAsArgFactory;
        $this->assignAndRootExprAndNodesToAddMatcher = $assignAndRootExprAndNodesToAddMatcher;
        $this->fluentChainMethodCallNodeAnalyzer = $fluentChainMethodCallNodeAnalyzer;
        $this->nonFluentChainMethodCallFactory = $nonFluentChainMethodCallFactory;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns fluent interface calls to classic ones.', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class UsedAsParameter
{
    public function someFunction(FluentClass $someClass)
    {
        $this->processFluentClass($someClass->someFunction()->otherFunction());
    }

    public function processFluentClass(FluentClass $someClass)
    {
    }
}

CODE_SAMPLE
, <<<'CODE_SAMPLE'
class UsedAsParameter
{
    public function someFunction(FluentClass $someClass)
    {
        $someClass->someFunction();
        $someClass->otherFunction();
        $this->processFluentClass($someClass);
    }

    public function processFluentClass(FluentClass $someClass)
    {
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        $parent = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$parent instanceof \PhpParser\Node\Arg) {
            return null;
        }
        $parentMethodCall = $parent->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$parentMethodCall instanceof \PhpParser\Node\Expr\MethodCall) {
            return null;
        }
        if (!$this->fluentChainMethodCallNodeAnalyzer->isLastChainMethodCall($node)) {
            return null;
        }
        // create instances from (new ...)->call, re-use from
        if ($node->var instanceof \PhpParser\Node\Expr\New_) {
            $this->refactorNew($node, $node->var);
            return null;
        }
        $assignAndRootExprAndNodesToAdd = $this->assignAndRootExprAndNodesToAddMatcher->match($node, \Rector\Defluent\ValueObject\FluentCallsKind::IN_ARGS);
        if (!$assignAndRootExprAndNodesToAdd instanceof \Rector\Defluent\ValueObject\AssignAndRootExprAndNodesToAdd) {
            return null;
        }
        $this->addNodesBeforeNode($assignAndRootExprAndNodesToAdd->getNodesToAdd(), $node);
        return $assignAndRootExprAndNodesToAdd->getRootCallerExpr();
    }
    /**
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PhpParser\Node\Expr\New_ $new
     */
    private function refactorNew($methodCall, $new) : void
    {
        if (!$this->newFluentChainMethodCallNodeAnalyzer->isNewMethodCallReturningSelf($methodCall)) {
            return;
        }
        $nodesToAdd = $this->nonFluentChainMethodCallFactory->createFromNewAndRootMethodCall($new, $methodCall);
        $newVariable = $this->variableFromNewFactory->create($new);
        $nodesToAdd[] = $this->fluentMethodCallAsArgFactory->createFluentAsArg($methodCall, $newVariable);
        $this->addNodesBeforeNode($nodesToAdd, $methodCall);
        $this->removeParentParent($methodCall);
    }
    /**
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     */
    private function removeParentParent($methodCall) : void
    {
        /** @var Arg $parent */
        $parent = $methodCall->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        /** @var MethodCall $parentParent */
        $parentParent = $parent->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        $this->removeNode($parentParent);
    }
}
