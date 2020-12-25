<?php

declare (strict_types=1);
namespace Rector\Core\PhpParser\Node\Manipulator;

use _PhpScoper50d83356d739\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\SOLID\Guard\VariableToConstantGuard;
final class VariableManipulator
{
    /**
     * @var CallableNodeTraverser
     */
    private $callableNodeTraverser;
    /**
     * @var AssignManipulator
     */
    private $assignManipulator;
    /**
     * @var BetterStandardPrinter
     */
    private $betterStandardPrinter;
    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;
    /**
     * @var ArrayManipulator
     */
    private $arrayManipulator;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var VariableToConstantGuard
     */
    private $variableToConstantGuard;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\ArrayManipulator $arrayManipulator, \Rector\Core\PhpParser\Node\Manipulator\AssignManipulator $assignManipulator, \Rector\Core\PhpParser\Node\BetterNodeFinder $betterNodeFinder, \Rector\Core\PhpParser\Printer\BetterStandardPrinter $betterStandardPrinter, \Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser $callableNodeTraverser, \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \Rector\SOLID\Guard\VariableToConstantGuard $variableToConstantGuard)
    {
        $this->callableNodeTraverser = $callableNodeTraverser;
        $this->assignManipulator = $assignManipulator;
        $this->betterStandardPrinter = $betterStandardPrinter;
        $this->betterNodeFinder = $betterNodeFinder;
        $this->arrayManipulator = $arrayManipulator;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->variableToConstantGuard = $variableToConstantGuard;
    }
    /**
     * @return Assign[]
     */
    public function collectScalarOrArrayAssignsOfVariable(\PhpParser\Node\Stmt\ClassMethod $classMethod) : array
    {
        $assignsOfArrayToVariable = [];
        $this->callableNodeTraverser->traverseNodesWithCallable((array) $classMethod->getStmts(), function (\PhpParser\Node $node) use(&$assignsOfArrayToVariable) {
            if (!$node instanceof \PhpParser\Node\Expr\Assign) {
                return null;
            }
            if (!$node->var instanceof \PhpParser\Node\Expr\Variable) {
                return null;
            }
            if (!$node->expr instanceof \PhpParser\Node\Expr\Array_ && !$node->expr instanceof \PhpParser\Node\Scalar) {
                return null;
            }
            if ($node->expr instanceof \PhpParser\Node\Scalar\Encapsed) {
                return null;
            }
            if ($node->expr instanceof \PhpParser\Node\Expr\Array_ && !$this->arrayManipulator->isArrayOnlyScalarValues($node->expr)) {
                return null;
            }
            if ($this->isTestCaseExpectedVariable($node->var)) {
                return null;
            }
            $assignsOfArrayToVariable[] = $node;
        });
        return $assignsOfArrayToVariable;
    }
    /**
     * @param Assign[] $assignsOfArrayToVariable
     * @return Assign[]
     */
    public function filterOutChangedVariables(array $assignsOfArrayToVariable, \PhpParser\Node\Stmt\ClassMethod $classMethod) : array
    {
        return \array_filter($assignsOfArrayToVariable, function (\PhpParser\Node\Expr\Assign $assign) use($classMethod) : bool {
            /** @var Variable $variable */
            $variable = $assign->var;
            return $this->isReadOnlyVariable($classMethod, $variable, $assign);
        });
    }
    private function isTestCaseExpectedVariable(\PhpParser\Node\Expr\Variable $variable) : bool
    {
        /** @var string $className */
        $className = $variable->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        if (!\_PhpScoper50d83356d739\Nette\Utils\Strings::endsWith($className, 'Test')) {
            return \false;
        }
        return $this->nodeNameResolver->isName($variable, 'expect*');
    }
    /**
     * Inspiration
     * @see \Rector\Core\PhpParser\Node\Manipulator\PropertyManipulator::isReadOnlyProperty()
     */
    private function isReadOnlyVariable(\PhpParser\Node\Stmt\ClassMethod $classMethod, \PhpParser\Node\Expr\Variable $variable, \PhpParser\Node\Expr\Assign $assign) : bool
    {
        $variableUsages = $this->collectVariableUsages($classMethod, $variable, $assign);
        foreach ($variableUsages as $variableUsage) {
            $parent = $variableUsage->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if ($parent instanceof \PhpParser\Node\Arg && !$this->variableToConstantGuard->isReadArg($parent)) {
                return \false;
            }
            if (!$this->assignManipulator->isLeftPartOfAssign($variableUsage)) {
                continue;
            }
            return \false;
        }
        return \true;
    }
    /**
     * @return Variable[]
     */
    private function collectVariableUsages(\PhpParser\Node\Stmt\ClassMethod $classMethod, \PhpParser\Node\Expr\Variable $variable, \PhpParser\Node\Expr\Assign $assign) : array
    {
        return $this->betterNodeFinder->find((array) $classMethod->getStmts(), function (\PhpParser\Node $node) use($variable, $assign) : bool {
            if (!$node instanceof \PhpParser\Node\Expr\Variable) {
                return \false;
            }
            // skip initialization
            $parentNode = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if ($parentNode === $assign) {
                return \false;
            }
            return $this->betterStandardPrinter->areNodesEqual($node, $variable);
        });
    }
}
