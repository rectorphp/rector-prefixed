<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\Reflection\BetterReflection\SourceLocator;

use _PhpScopere8e811afab72\PhpParser\BuilderHelpers;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Namespace_;
use _PhpScopere8e811afab72\PhpParser\NodeVisitorAbstract;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Exception\InvalidConstantNode;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Util\ConstantNodeChecker;
class CachingVisitor extends \_PhpScopere8e811afab72\PhpParser\NodeVisitorAbstract
{
    /** @var string */
    private $fileName;
    /** @var array<string, array<FetchedNode<\PhpParser\Node\Stmt\ClassLike>>> */
    private $classNodes;
    /** @var array<string, FetchedNode<\PhpParser\Node\Stmt\Function_>> */
    private $functionNodes;
    /** @var array<int, FetchedNode<\PhpParser\Node\Stmt\Const_|\PhpParser\Node\Expr\FuncCall>> */
    private $constantNodes;
    /** @var \PhpParser\Node\Stmt\Namespace_|null */
    private $currentNamespaceNode = null;
    public function enterNode(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?int
    {
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Namespace_) {
            $this->currentNamespaceNode = $node;
        }
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassLike) {
            if ($node->name !== null) {
                $fullClassName = $node->name->toString();
                if ($this->currentNamespaceNode !== null && $this->currentNamespaceNode->name !== null) {
                    $fullClassName = $this->currentNamespaceNode->name . '\\' . $fullClassName;
                }
                $this->classNodes[\strtolower($fullClassName)][] = new \_PhpScopere8e811afab72\PHPStan\Reflection\BetterReflection\SourceLocator\FetchedNode($node, $this->currentNamespaceNode, $this->fileName);
            }
            return \_PhpScopere8e811afab72\PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Function_) {
            $this->functionNodes[\strtolower($node->namespacedName->toString())] = new \_PhpScopere8e811afab72\PHPStan\Reflection\BetterReflection\SourceLocator\FetchedNode($node, $this->currentNamespaceNode, $this->fileName);
            return \_PhpScopere8e811afab72\PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Const_) {
            $this->constantNodes[] = new \_PhpScopere8e811afab72\PHPStan\Reflection\BetterReflection\SourceLocator\FetchedNode($node, $this->currentNamespaceNode, $this->fileName);
            return \_PhpScopere8e811afab72\PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall) {
            try {
                \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Util\ConstantNodeChecker::assertValidDefineFunctionCall($node);
            } catch (\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Exception\InvalidConstantNode $e) {
                return null;
            }
            /** @var \PhpParser\Node\Scalar\String_ $nameNode */
            $nameNode = $node->args[0]->value;
            $constantName = $nameNode->value;
            if (\defined($constantName)) {
                $constantValue = \constant($constantName);
                $node->args[1]->value = \_PhpScopere8e811afab72\PhpParser\BuilderHelpers::normalizeValue($constantValue);
            }
            $constantNode = new \_PhpScopere8e811afab72\PHPStan\Reflection\BetterReflection\SourceLocator\FetchedNode($node, $this->currentNamespaceNode, $this->fileName);
            $this->constantNodes[] = $constantNode;
            return \_PhpScopere8e811afab72\PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
        return null;
    }
    /**
     * @param \PhpParser\Node $node
     * @return null
     */
    public function leaveNode(\_PhpScopere8e811afab72\PhpParser\Node $node)
    {
        if (!$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Namespace_) {
            return null;
        }
        $this->currentNamespaceNode = null;
        return null;
    }
    /**
     * @return array<string, array<FetchedNode<\PhpParser\Node\Stmt\ClassLike>>>
     */
    public function getClassNodes() : array
    {
        return $this->classNodes;
    }
    /**
     * @return array<string, FetchedNode<\PhpParser\Node\Stmt\Function_>>
     */
    public function getFunctionNodes() : array
    {
        return $this->functionNodes;
    }
    /**
     * @return array<int, FetchedNode<\PhpParser\Node\Stmt\Const_|\PhpParser\Node\Expr\FuncCall>>
     */
    public function getConstantNodes() : array
    {
        return $this->constantNodes;
    }
    public function reset(string $fileName) : void
    {
        $this->classNodes = [];
        $this->functionNodes = [];
        $this->constantNodes = [];
        $this->fileName = $fileName;
    }
}
