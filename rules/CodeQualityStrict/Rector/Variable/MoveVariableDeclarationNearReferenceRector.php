<?php

declare (strict_types=1);
namespace Rector\CodeQualityStrict\Rector\Variable;

use RectorPrefix20210317\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\While_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeNestingScope\NodeFinder\ScopeAwareNodeFinder;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\CodeQualityStrict\Rector\Variable\MoveVariableDeclarationNearReferenceRector\MoveVariableDeclarationNearReferenceRectorTest
 */
final class MoveVariableDeclarationNearReferenceRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var ScopeAwareNodeFinder
     */
    private $scopeAwareNodeFinder;
    /**
     * @param \Rector\NodeNestingScope\NodeFinder\ScopeAwareNodeFinder $scopeAwareNodeFinder
     */
    public function __construct($scopeAwareNodeFinder)
    {
        $this->scopeAwareNodeFinder = $scopeAwareNodeFinder;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Move variable declaration near its reference', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$var = 1;
if ($condition === null) {
    return $var;
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
if ($condition === null) {
    $var = 1;
    return $var;
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\Variable::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        $parent = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!($parent instanceof \PhpParser\Node\Expr\Assign && $parent->var === $node)) {
            return null;
        }
        if ($parent->expr instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            return null;
        }
        $expression = $parent->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$expression instanceof \PhpParser\Node\Stmt\Expression) {
            return null;
        }
        if ($this->isUsedAsArraykeyOrInsideIfCondition($expression, $node)) {
            return null;
        }
        if ($this->hasPropertyInExpr($expression, $parent->expr)) {
            return null;
        }
        if ($this->shouldSkipReAssign($expression, $parent)) {
            return null;
        }
        $variable = $this->getUsageInNextStmts($expression, $node);
        if (!$variable instanceof \PhpParser\Node\Expr\Variable) {
            return null;
        }
        /** @var Node $usageStmt */
        $usageStmt = $variable->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CURRENT_STATEMENT);
        if ($this->isInsideLoopStmts($usageStmt)) {
            return null;
        }
        $this->addNodeBeforeNode($expression, $usageStmt);
        $this->removeNode($expression);
        return $node;
    }
    /**
     * @param \PhpParser\Node\Stmt\Expression $expression
     * @param \PhpParser\Node\Expr\Variable $variable
     */
    private function isUsedAsArraykeyOrInsideIfCondition($expression, $variable) : bool
    {
        $parentExpression = $expression->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($this->isUsedAsArrayKey($parentExpression, $variable)) {
            return \true;
        }
        return $this->isInsideCondition($expression);
    }
    /**
     * @param \PhpParser\Node\Stmt\Expression $expression
     * @param \PhpParser\Node\Expr $expr
     */
    private function hasPropertyInExpr($expression, $expr) : bool
    {
        return (bool) $this->betterNodeFinder->findFirst($expr, function (\PhpParser\Node $node) : bool {
            return $node instanceof \PhpParser\Node\Expr\PropertyFetch || $node instanceof \PhpParser\Node\Expr\StaticPropertyFetch;
        });
    }
    /**
     * @param \PhpParser\Node\Stmt\Expression $expression
     * @param \PhpParser\Node\Expr\Assign $assign
     */
    private function shouldSkipReAssign($expression, $assign) : bool
    {
        if ($this->hasReAssign($expression, $assign->var)) {
            return \true;
        }
        return $this->hasReAssign($expression, $assign->expr);
    }
    /**
     * @param \PhpParser\Node\Stmt\Expression $expression
     * @param \PhpParser\Node\Expr\Variable $variable
     */
    private function getUsageInNextStmts($expression, $variable) : ?\PhpParser\Node\Expr\Variable
    {
        /** @var Node|null $next */
        $next = $expression->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        if (!$next instanceof \PhpParser\Node) {
            return null;
        }
        if ($this->hasCall($next)) {
            return null;
        }
        $countFound = $this->getCountFound($next, $variable);
        if ($countFound === 0) {
            return null;
        }
        if ($countFound >= 2) {
            return null;
        }
        $nextVariable = $this->getSameVarName([$next], $variable);
        if ($nextVariable instanceof \PhpParser\Node\Expr\Variable) {
            return $nextVariable;
        }
        return $this->getSameVarNameInNexts($next, $variable);
    }
    /**
     * @param \PhpParser\Node $node
     */
    private function isInsideLoopStmts($node) : bool
    {
        $loopNode = $this->betterNodeFinder->findParentTypes($node, [\PhpParser\Node\Stmt\For_::class, \PhpParser\Node\Stmt\While_::class, \PhpParser\Node\Stmt\Foreach_::class, \PhpParser\Node\Stmt\Do_::class]);
        return (bool) $loopNode;
    }
    /**
     * @param \PhpParser\Node|null $node
     * @param \PhpParser\Node\Expr\Variable $variable
     */
    private function isUsedAsArrayKey($node, $variable) : bool
    {
        if (!$node instanceof \PhpParser\Node) {
            return \false;
        }
        /** @var ArrayDimFetch[] $arrayDimFetches */
        $arrayDimFetches = $this->betterNodeFinder->findInstanceOf($node, \PhpParser\Node\Expr\ArrayDimFetch::class);
        foreach ($arrayDimFetches as $arrayDimFetch) {
            /** @var Node|null $dim */
            $dim = $arrayDimFetch->dim;
            if (!$dim instanceof \PhpParser\Node) {
                continue;
            }
            $isFoundInKey = (bool) $this->betterNodeFinder->findFirst($dim, function (\PhpParser\Node $node) use($variable) : bool {
                return $this->nodeComparator->areNodesEqual($node, $variable);
            });
            if ($isFoundInKey) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @param \PhpParser\Node\Stmt\Expression $expression
     */
    private function isInsideCondition($expression) : bool
    {
        return (bool) $this->scopeAwareNodeFinder->findParentType($expression, [\PhpParser\Node\Stmt\If_::class, \PhpParser\Node\Stmt\Else_::class, \PhpParser\Node\Stmt\ElseIf_::class]);
    }
    /**
     * @param \PhpParser\Node\Stmt\Expression $expression
     * @param \PhpParser\Node\Expr $expr
     */
    private function hasReAssign($expression, $expr) : bool
    {
        $next = $expression->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        $exprValues = $this->betterNodeFinder->find($expr, function (\PhpParser\Node $node) : bool {
            return $node instanceof \PhpParser\Node\Expr\Variable;
        });
        if ($exprValues === []) {
            return \false;
        }
        while ($next) {
            foreach ($exprValues as $exprValue) {
                $isReAssign = (bool) $this->betterNodeFinder->findFirst($next, function (\PhpParser\Node $node) : bool {
                    $parent = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
                    $node = $this->mayBeArrayDimFetch($node);
                    if (!$parent instanceof \PhpParser\Node\Expr\Assign) {
                        return \false;
                    }
                    return (string) $this->getName($node) === (string) $this->getName($parent->var);
                });
                if (!$isReAssign) {
                    continue;
                }
                return \true;
            }
            $next = $next->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        }
        return \false;
    }
    /**
     * @param \PhpParser\Node $node
     */
    private function hasCall($node) : bool
    {
        return (bool) $this->betterNodeFinder->findFirst($node, function (\PhpParser\Node $n) : bool {
            if ($n instanceof \PhpParser\Node\Expr\StaticCall) {
                return \true;
            }
            if ($n instanceof \PhpParser\Node\Expr\MethodCall) {
                return \true;
            }
            if (!$n instanceof \PhpParser\Node\Expr\FuncCall) {
                return \false;
            }
            $funcName = $this->getName($n);
            if ($funcName === null) {
                return \false;
            }
            return \RectorPrefix20210317\Nette\Utils\Strings::startsWith($funcName, 'ob_');
        });
    }
    /**
     * @param \PhpParser\Node $node
     * @param \PhpParser\Node\Expr\Variable $variable
     */
    private function getCountFound($node, $variable) : int
    {
        $countFound = 0;
        while ($node) {
            $isFound = (bool) $this->getSameVarName([$node], $variable);
            if ($isFound) {
                ++$countFound;
            }
            $countFound = $this->countWithElseIf($node, $variable, $countFound);
            $countFound = $this->countWithTryCatch($node, $variable, $countFound);
            $countFound = $this->countWithSwitchCase($node, $variable, $countFound);
            /** @var Node|null $node */
            $node = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        }
        return $countFound;
    }
    /**
     * @param array<int, Node|null> $multiNodes
     * @param \PhpParser\Node\Expr\Variable $variable
     */
    private function getSameVarName($multiNodes, $variable) : ?\PhpParser\Node\Expr\Variable
    {
        foreach ($multiNodes as $multiNode) {
            if ($multiNode === null) {
                continue;
            }
            /** @var Variable|null $found */
            $found = $this->betterNodeFinder->findFirst($multiNode, function (\PhpParser\Node $n) use($variable) : bool {
                $n = $this->mayBeArrayDimFetch($n);
                if (!$n instanceof \PhpParser\Node\Expr\Variable) {
                    return \false;
                }
                return $this->isName($n, (string) $this->getName($variable));
            });
            if ($found !== null) {
                return $found;
            }
        }
        return null;
    }
    /**
     * @param \PhpParser\Node $node
     * @param \PhpParser\Node\Expr\Variable $variable
     */
    private function getSameVarNameInNexts($node, $variable) : ?\PhpParser\Node\Expr\Variable
    {
        while ($node) {
            $found = $this->getSameVarName([$node], $variable);
            if ($found instanceof \PhpParser\Node\Expr\Variable) {
                return $found;
            }
            /** @var Node|null $node */
            $node = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        }
        return null;
    }
    /**
     * @param \PhpParser\Node $node
     */
    private function mayBeArrayDimFetch($node) : \PhpParser\Node
    {
        $parent = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parent instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            $node = $parent->var;
        }
        return $node;
    }
    /**
     * @param \PhpParser\Node $node
     * @param \PhpParser\Node\Expr\Variable $variable
     * @param int $countFound
     */
    private function countWithElseIf($node, $variable, $countFound) : int
    {
        if (!$node instanceof \PhpParser\Node\Stmt\If_) {
            return $countFound;
        }
        $isFoundElseIf = (bool) $this->getSameVarName($node->elseifs, $variable);
        $isFoundElse = (bool) $this->getSameVarName([$node->else], $variable);
        if ($isFoundElseIf || $isFoundElse) {
            ++$countFound;
        }
        return $countFound;
    }
    /**
     * @param \PhpParser\Node $node
     * @param \PhpParser\Node\Expr\Variable $variable
     * @param int $countFound
     */
    private function countWithTryCatch($node, $variable, $countFound) : int
    {
        if (!$node instanceof \PhpParser\Node\Stmt\TryCatch) {
            return $countFound;
        }
        $isFoundInCatch = (bool) $this->getSameVarName($node->catches, $variable);
        $isFoundInFinally = (bool) $this->getSameVarName([$node->finally], $variable);
        if ($isFoundInCatch || $isFoundInFinally) {
            ++$countFound;
        }
        return $countFound;
    }
    /**
     * @param \PhpParser\Node $node
     * @param \PhpParser\Node\Expr\Variable $variable
     * @param int $countFound
     */
    private function countWithSwitchCase($node, $variable, $countFound) : int
    {
        if (!$node instanceof \PhpParser\Node\Stmt\Switch_) {
            return $countFound;
        }
        $isFoundInCases = (bool) $this->getSameVarName($node->cases, $variable);
        if ($isFoundInCases) {
            ++$countFound;
        }
        return $countFound;
    }
}
