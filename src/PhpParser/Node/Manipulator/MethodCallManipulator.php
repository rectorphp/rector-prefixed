<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Assign;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Variable;
use _PhpScopere8e811afab72\PhpParser\Node\FunctionLike;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\Node\BetterNodeFinder;
use _PhpScopere8e811afab72\Rector\Defluent\NodeAnalyzer\FluentChainMethodCallNodeAnalyzer;
use _PhpScopere8e811afab72\Rector\NodeNameResolver\NodeNameResolver;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
final class MethodCallManipulator
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;
    /**
     * @var FluentChainMethodCallNodeAnalyzer
     */
    private $fluentChainMethodCallNodeAnalyzer;
    public function __construct(\_PhpScopere8e811afab72\Rector\Core\PhpParser\Node\BetterNodeFinder $betterNodeFinder, \_PhpScopere8e811afab72\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \_PhpScopere8e811afab72\Rector\Defluent\NodeAnalyzer\FluentChainMethodCallNodeAnalyzer $fluentChainMethodCallNodeAnalyzer)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->betterNodeFinder = $betterNodeFinder;
        $this->fluentChainMethodCallNodeAnalyzer = $fluentChainMethodCallNodeAnalyzer;
    }
    /**
     * @return string[]
     */
    public function findMethodCallNamesOnVariable(\_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable $variable) : array
    {
        $methodCallsOnVariable = $this->findMethodCallsOnVariable($variable);
        $methodCallNamesOnVariable = [];
        foreach ($methodCallsOnVariable as $methodCallOnVariable) {
            $methodName = $this->nodeNameResolver->getName($methodCallOnVariable->name);
            if ($methodName === null) {
                continue;
            }
            $methodCallNamesOnVariable[] = $methodName;
        }
        return \array_unique($methodCallNamesOnVariable);
    }
    /**
     * @return MethodCall[]
     */
    public function findMethodCallsIncludingChain(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall) : array
    {
        $chainMethodCalls = [];
        // 1. collect method chain call
        $currentMethodCallee = $methodCall->var;
        while ($currentMethodCallee instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
            $chainMethodCalls[] = $currentMethodCallee;
            $currentMethodCallee = $currentMethodCallee->var;
        }
        // 2. collect on-same-variable calls
        $onVariableMethodCalls = [];
        if ($currentMethodCallee instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable) {
            $onVariableMethodCalls = $this->findMethodCallsOnVariable($currentMethodCallee);
        }
        $methodCalls = \array_merge($chainMethodCalls, $onVariableMethodCalls);
        return $this->uniquateObjects($methodCalls);
    }
    public function findAssignToVariable(\_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable $variable) : ?\_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign
    {
        /** @var Node|null $parentNode */
        $parentNode = $variable->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parentNode === null) {
            return null;
        }
        $variableName = $this->nodeNameResolver->getName($variable);
        if ($variableName === null) {
            return null;
        }
        do {
            $assign = $this->findAssignToVariableName($parentNode, $variableName);
            if ($assign instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign) {
                return $assign;
            }
            $parentNode = $this->resolvePreviousNodeInSameScope($parentNode);
        } while ($parentNode instanceof \_PhpScopere8e811afab72\PhpParser\Node && !$parentNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\FunctionLike);
        return null;
    }
    /**
     * @return MethodCall[]
     */
    public function findMethodCallsOnVariable(\_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable $variable) : array
    {
        // get scope node, e.g. parent function call, method call or anonymous function
        /** @var ClassMethod|null $classMethod */
        $classMethod = $variable->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::METHOD_NODE);
        if ($classMethod === null) {
            return [];
        }
        $variableName = $this->nodeNameResolver->getName($variable);
        return $this->betterNodeFinder->find((array) $classMethod->stmts, function (\_PhpScopere8e811afab72\PhpParser\Node $node) use($variableName) : bool {
            if (!$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
                return \false;
            }
            // cover fluent interfaces too
            $callerNode = $this->fluentChainMethodCallNodeAnalyzer->resolveRootExpr($node);
            if (!$callerNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable) {
                return \false;
            }
            return $this->nodeNameResolver->isName($callerNode, $variableName);
        });
    }
    /**
     * @see https://stackoverflow.com/a/4507991/1348344
     * @param object[] $objects
     * @return object[]
     *
     * @template T
     * @phpstan-param array<T>|T[] $objects
     * @phpstan-return array<T>|T[]
     */
    private function uniquateObjects(array $objects) : array
    {
        $uniqueObjects = [];
        foreach ($objects as $object) {
            if (\in_array($object, $uniqueObjects, \true)) {
                continue;
            }
            $uniqueObjects[] = $object;
        }
        // re-index
        return \array_values($uniqueObjects);
    }
    private function findAssignToVariableName(\_PhpScopere8e811afab72\PhpParser\Node $node, string $variableName) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        return $this->betterNodeFinder->findFirst($node, function (\_PhpScopere8e811afab72\PhpParser\Node $node) use($variableName) : bool {
            if (!$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign) {
                return \false;
            }
            if (!$node->var instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable) {
                return \false;
            }
            return $this->nodeNameResolver->isName($node->var, $variableName);
        });
    }
    private function resolvePreviousNodeInSameScope(\_PhpScopere8e811afab72\PhpParser\Node $parentNode) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        $previousParentNode = $parentNode;
        $parentNode = $parentNode->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$parentNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\FunctionLike) {
            // is about to leave → try previous expression
            $previousStatement = $previousParentNode->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::PREVIOUS_STATEMENT);
            if ($previousStatement instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression) {
                return $previousStatement->expr;
            }
        }
        return $parentNode;
    }
}
