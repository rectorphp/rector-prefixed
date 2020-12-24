<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Php70\Rector\FuncCall;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayDimFetch;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Assign;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\AssignOp;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\AssignRef;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\PropertyFetch;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\StaticPropertyFetch;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Variable;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_;
use _PhpScopere8e811afab72\PHPStan\Analyser\MutatingScope;
use _PhpScopere8e811afab72\PHPStan\Analyser\Scope;
use _PhpScopere8e811afab72\PHPStan\Reflection\ParameterReflection;
use _PhpScopere8e811afab72\PHPStan\Type\MixedType;
use _PhpScopere8e811afab72\Rector\Core\PHPStan\Reflection\CallReflectionResolver;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\NetteKdyby\Naming\VariableNaming;
use _PhpScopere8e811afab72\Rector\NodeNestingScope\ParentScopeFinder;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Rector\Php70\ValueObject\VariableAssignPair;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://www.php.net/manual/en/migration70.incompatible.php
 *
 * @see \Rector\Php70\Tests\Rector\FuncCall\NonVariableToVariableOnFunctionCallRector\NonVariableToVariableOnFunctionCallRectorTest
 */
final class NonVariableToVariableOnFunctionCallRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var CallReflectionResolver
     */
    private $callReflectionResolver;
    /**
     * @var VariableNaming
     */
    private $variableNaming;
    /**
     * @var ParentScopeFinder
     */
    private $parentScopeFinder;
    public function __construct(\_PhpScopere8e811afab72\Rector\Core\PHPStan\Reflection\CallReflectionResolver $callReflectionResolver, \_PhpScopere8e811afab72\Rector\NetteKdyby\Naming\VariableNaming $variableNaming, \_PhpScopere8e811afab72\Rector\NodeNestingScope\ParentScopeFinder $parentScopeFinder)
    {
        $this->callReflectionResolver = $callReflectionResolver;
        $this->variableNaming = $variableNaming;
        $this->parentScopeFinder = $parentScopeFinder;
    }
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Transform non variable like arguments to variable where a function or method expects an argument passed by reference', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('reset(a());', '$a = a(); reset($a);')]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall::class, \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall::class, \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param FuncCall|MethodCall|StaticCall $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        $arguments = $this->getNonVariableArguments($node);
        if ($arguments === []) {
            return null;
        }
        $scopeNode = $this->parentScopeFinder->find($node);
        if ($scopeNode === null) {
            return null;
        }
        $currentScope = $scopeNode->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE);
        if (!$currentScope instanceof \_PhpScopere8e811afab72\PHPStan\Analyser\MutatingScope) {
            return null;
        }
        foreach ($arguments as $key => $argument) {
            $replacements = $this->getReplacementsFor($argument, $currentScope, $scopeNode);
            $current = $node->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::CURRENT_STATEMENT);
            $currentStatement = $node->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::CURRENT_STATEMENT);
            $this->addNodeBeforeNode($replacements->getAssign(), $current instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_ ? $current : $currentStatement);
            $node->args[$key]->value = $replacements->getVariable();
            // add variable name to scope, so we prevent duplication of new variable of the same name
            $currentScope = $currentScope->assignExpression($replacements->getVariable(), $currentScope->getType($replacements->getVariable()));
        }
        $scopeNode->setAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE, $currentScope);
        return $node;
    }
    /**
     * @param FuncCall|MethodCall|StaticCall $node
     *
     * @return Expr[]
     */
    private function getNonVariableArguments(\_PhpScopere8e811afab72\PhpParser\Node $node) : array
    {
        $arguments = [];
        $parametersAcceptor = $this->callReflectionResolver->resolveParametersAcceptor($this->callReflectionResolver->resolveCall($node), $node);
        if ($parametersAcceptor === null) {
            return [];
        }
        /** @var ParameterReflection $parameterReflection */
        foreach ($parametersAcceptor->getParameters() as $key => $parameterReflection) {
            // omitted optional parameter
            if (!isset($node->args[$key])) {
                continue;
            }
            if ($parameterReflection->passedByReference()->no()) {
                continue;
            }
            $argument = $node->args[$key]->value;
            if ($this->isVariableLikeNode($argument)) {
                continue;
            }
            $arguments[$key] = $argument;
        }
        return $arguments;
    }
    private function getReplacementsFor(\_PhpScopere8e811afab72\PhpParser\Node\Expr $expr, \_PhpScopere8e811afab72\PHPStan\Analyser\MutatingScope $mutatingScope, \_PhpScopere8e811afab72\PhpParser\Node $scopeNode) : \_PhpScopere8e811afab72\Rector\Php70\ValueObject\VariableAssignPair
    {
        /** @var Assign|AssignOp|AssignRef $expr */
        if ($this->isAssign($expr) && $this->isVariableLikeNode($expr->var)) {
            return new \_PhpScopere8e811afab72\Rector\Php70\ValueObject\VariableAssignPair($expr->var, $expr);
        }
        $variableName = $this->variableNaming->resolveFromNodeWithScopeCountAndFallbackName($expr, $mutatingScope, 'tmp');
        $variable = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable($variableName);
        // add a new scope with this variable
        $newVariableAwareScope = $mutatingScope->assignExpression($variable, new \_PhpScopere8e811afab72\PHPStan\Type\MixedType());
        $scopeNode->setAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE, $newVariableAwareScope);
        return new \_PhpScopere8e811afab72\Rector\Php70\ValueObject\VariableAssignPair($variable, new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign($variable, $expr));
    }
    private function isVariableLikeNode(\_PhpScopere8e811afab72\PhpParser\Node $node) : bool
    {
        return $node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable || $node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayDimFetch || $node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\PropertyFetch || $node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticPropertyFetch;
    }
    private function isAssign(\_PhpScopere8e811afab72\PhpParser\Node\Expr $expr) : bool
    {
        if ($expr instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign) {
            return \true;
        }
        if ($expr instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\AssignRef) {
            return \true;
        }
        return $expr instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\AssignOp;
    }
}
