<?php

declare (strict_types=1);
namespace Rector\Naming;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser;
use Rector\Naming\PhpDoc\VarTagValueNodeRenamer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
final class VariableRenamer
{
    /**
     * @var CallableNodeTraverser
     */
    private $callableNodeTraverser;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var VarTagValueNodeRenamer
     */
    private $varTagValueNodeRenamer;
    public function __construct(\Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser $callableNodeTraverser, \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \Rector\Naming\PhpDoc\VarTagValueNodeRenamer $varTagValueNodeRenamer)
    {
        $this->callableNodeTraverser = $callableNodeTraverser;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->varTagValueNodeRenamer = $varTagValueNodeRenamer;
    }
    /**
     * @param ClassMethod|Function_|Closure $functionLike
     */
    public function renameVariableInFunctionLike(\PhpParser\Node\FunctionLike $functionLike, ?\PhpParser\Node\Expr\Assign $assign = null, string $oldName, string $expectedName) : void
    {
        $isRenamingActive = \false;
        if ($assign === null) {
            $isRenamingActive = \true;
        }
        $this->callableNodeTraverser->traverseNodesWithCallable((array) $functionLike->stmts, function (\PhpParser\Node $node) use($oldName, $expectedName, $assign, &$isRenamingActive) : ?Variable {
            if ($assign !== null && $node === $assign) {
                $isRenamingActive = \true;
                return null;
            }
            if (!$node instanceof \PhpParser\Node\Expr\Variable) {
                return null;
            }
            // skip param names
            $parent = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if ($parent instanceof \PhpParser\Node\Param) {
                return null;
            }
            // TODO: Remove in next PR (with above param check?),
            // TODO: Should be implemented in BreakingVariableRenameGuard::shouldSkipParam()
            if ($this->isParamInParentFunction($node)) {
                return null;
            }
            if (!$isRenamingActive) {
                return null;
            }
            return $this->renameVariableIfMatchesName($node, $oldName, $expectedName);
        });
    }
    private function isParamInParentFunction(\PhpParser\Node\Expr\Variable $variable) : bool
    {
        /** @var Closure|null $closure */
        $closure = $variable->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLOSURE_NODE);
        if ($closure === null) {
            return \false;
        }
        $variableName = $this->nodeNameResolver->getName($variable);
        if ($variableName === null) {
            return \false;
        }
        foreach ($closure->params as $param) {
            if ($this->nodeNameResolver->isName($param, $variableName)) {
                return \true;
            }
        }
        return \false;
    }
    private function renameVariableIfMatchesName(\PhpParser\Node\Expr\Variable $variable, string $oldName, string $expectedName) : ?\PhpParser\Node\Expr\Variable
    {
        if (!$this->nodeNameResolver->isName($variable, $oldName)) {
            return null;
        }
        $variable->name = $expectedName;
        $this->varTagValueNodeRenamer->renameAssignVarTagVariableName($variable, $oldName, $expectedName);
        return $variable;
    }
}