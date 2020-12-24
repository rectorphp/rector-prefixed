<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Php72\Rector\Assign;

use _PhpScoperb75b35f52b74\PhpParser\BuilderHelpers;
use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Arg;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayDimFetch;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\List_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\While_;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @sponsor Thanks https://twitter.com/afilina & Zenika (CAN) for sponsoring this rule - visit them on https://zenika.ca/en/en
 *
 * @see \Rector\Php72\Tests\Rector\Assign\ReplaceEachAssignmentWithKeyCurrentRector\ReplaceEachAssignmentWithKeyCurrentRectorTest
 */
final class ReplaceEachAssignmentWithKeyCurrentRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const KEY = 'key';
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Replace each() assign outside loop', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$array = ['b' => 1, 'a' => 2];
$eachedArray = each($array);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$array = ['b' => 1, 'a' => 2];
$eachedArray[1] = current($array);
$eachedArray['value'] = current($array);
$eachedArray[0] = key($array);
$eachedArray['key'] = key($array);
next($array);
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign::class];
    }
    /**
     * @param Assign $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        /** @var FuncCall $eachFuncCall */
        $eachFuncCall = $node->expr;
        $eachedVariable = $eachFuncCall->args[0]->value;
        $assignVariable = $node->var;
        $newNodes = $this->createNewNodes($assignVariable, $eachedVariable);
        $this->addNodesAfterNode($newNodes, $node);
        $this->removeNode($node);
        return null;
    }
    private function shouldSkip(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign $assign) : bool
    {
        if (!$this->isFuncCallName($assign->expr, 'each')) {
            return \true;
        }
        $parentNode = $assign->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parentNode instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\While_) {
            return \true;
        }
        // skip assign to List
        if (!$parentNode instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign) {
            return \false;
        }
        return $parentNode->var instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\List_;
    }
    /**
     * @return array<int, Assign|FuncCall>
     */
    private function createNewNodes(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr $assignVariable, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr $eachedVariable) : array
    {
        $newNodes = [];
        $newNodes[] = $this->createDimFetchAssignWithFuncCall($assignVariable, $eachedVariable, 1, 'current');
        $newNodes[] = $this->createDimFetchAssignWithFuncCall($assignVariable, $eachedVariable, 'value', 'current');
        $newNodes[] = $this->createDimFetchAssignWithFuncCall($assignVariable, $eachedVariable, 0, self::KEY);
        $newNodes[] = $this->createDimFetchAssignWithFuncCall($assignVariable, $eachedVariable, self::KEY, self::KEY);
        $newNodes[] = $this->createFuncCall('next', [new \_PhpScoperb75b35f52b74\PhpParser\Node\Arg($eachedVariable)]);
        return $newNodes;
    }
    /**
     * @param string|int $dimValue
     */
    private function createDimFetchAssignWithFuncCall(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr $assignVariable, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr $eachedVariable, $dimValue, string $functionName) : \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign
    {
        $dim = \_PhpScoperb75b35f52b74\PhpParser\BuilderHelpers::normalizeValue($dimValue);
        $arrayDimFetch = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayDimFetch($assignVariable, $dim);
        return new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign($arrayDimFetch, $this->createFuncCall($functionName, [new \_PhpScoperb75b35f52b74\PhpParser\Node\Arg($eachedVariable)]));
    }
}
