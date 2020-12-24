<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Php80\Rector\Switch_;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Match_;
use _PhpScoperb75b35f52b74\PhpParser\Node\MatchArm;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Expression;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Return_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Switch_;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Rector\Php80\NodeAnalyzer\SwitchAnalyzer;
use _PhpScoperb75b35f52b74\Rector\Php80\NodeResolver\SwitchExprsResolver;
use _PhpScoperb75b35f52b74\Rector\Php80\ValueObject\CondAndExpr;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://wiki.php.net/rfc/match_expression_v2
 * @see https://3v4l.org/572T5
 *
 * @see \Rector\Php80\Tests\Rector\Switch_\ChangeSwitchToMatchRector\ChangeSwitchToMatchRectorTest
 */
final class ChangeSwitchToMatchRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @var SwitchExprsResolver
     */
    private $switchExprsResolver;
    /**
     * @var SwitchAnalyzer
     */
    private $switchAnalyzer;
    /**
     * @var Expr|null
     */
    private $assignExpr;
    public function __construct(\_PhpScoperb75b35f52b74\Rector\Php80\NodeResolver\SwitchExprsResolver $switchExprsResolver, \_PhpScoperb75b35f52b74\Rector\Php80\NodeAnalyzer\SwitchAnalyzer $switchAnalyzer)
    {
        $this->switchExprsResolver = $switchExprsResolver;
        $this->switchAnalyzer = $switchAnalyzer;
    }
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change switch() to match()', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        switch ($this->lexer->lookahead['type']) {
            case Lexer::T_SELECT:
                $statement = $this->SelectStatement();
                break;

            case Lexer::T_UPDATE:
                $statement = $this->UpdateStatement();
                break;

            default:
                $statement = $this->syntaxError('SELECT, UPDATE or DELETE');
                break;
        }
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $statement = match ($this->lexer->lookahead['type']) {
            Lexer::T_SELECT => $this->SelectStatement(),
            Lexer::T_UPDATE => $this->UpdateStatement(),
            default => $this->syntaxError('SELECT, UPDATE or DELETE'),
        };
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Switch_::class];
    }
    /**
     * @param Switch_ $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if ($this->shouldSkipSwitch($node)) {
            return null;
        }
        $condAndExprs = $this->switchExprsResolver->resolve($node);
        if ($condAndExprs === []) {
            return null;
        }
        if (!$this->haveCondAndExprsMatchPotential($condAndExprs)) {
            return null;
        }
        $this->assignExpr = null;
        $isReturn = \false;
        foreach ($condAndExprs as $condAndExpr) {
            if ($condAndExpr->getKind() === \_PhpScoperb75b35f52b74\Rector\Php80\ValueObject\CondAndExpr::TYPE_RETURN) {
                $isReturn = \true;
                break;
            }
        }
        $matchArms = $this->createMatchArmsFromCases($condAndExprs);
        $match = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Match_($node->cond, $matchArms);
        if ($isReturn) {
            return new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Return_($match);
        }
        if ($this->assignExpr) {
            return new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign($this->assignExpr, $match);
        }
        return $match;
    }
    private function shouldSkipSwitch(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Switch_ $switch) : bool
    {
        if (!$this->switchAnalyzer->hasEachCaseBreak($switch)) {
            return \true;
        }
        return !$this->switchAnalyzer->hasEachCaseSingleStmt($switch);
    }
    /**
     * @param CondAndExpr[] $condAndExprs
     */
    private function haveCondAndExprsMatchPotential(array $condAndExprs) : bool
    {
        $uniqueCondAndExprKinds = $this->resolveUniqueKinds($condAndExprs);
        if (\count($uniqueCondAndExprKinds) > 1) {
            return \false;
        }
        $assignVariableNames = [];
        foreach ($condAndExprs as $condAndExpr) {
            $expr = $condAndExpr->getExpr();
            if (!$expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign) {
                continue;
            }
            $assignVariableNames[] = $this->getName($expr->var);
        }
        $assignVariableNames = \array_unique($assignVariableNames);
        return \count($assignVariableNames) <= 1;
    }
    /**
     * @param CondAndExpr[] $condAndExprs
     * @return MatchArm[]
     */
    private function createMatchArmsFromCases(array $condAndExprs) : array
    {
        $matchArms = [];
        foreach ($condAndExprs as $condAndExpr) {
            $expr = $condAndExpr->getExpr();
            if ($expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign) {
                $this->assignExpr = $expr->var;
                $expr = $expr->expr;
            }
            $condExpr = $condAndExpr->getCondExpr();
            $condList = $condExpr === null ? null : [$condExpr];
            $matchArms[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\MatchArm($condList, $expr);
        }
        return $matchArms;
    }
    /**
     * @param CondAndExpr[] $condAndExprs
     * @return string[]
     */
    private function resolveUniqueKinds(array $condAndExprs) : array
    {
        $condAndExprKinds = [];
        foreach ($condAndExprs as $condAndExpr) {
            $condAndExprKinds[] = $condAndExpr->getKind();
        }
        return \array_unique($condAndExprKinds);
    }
}
