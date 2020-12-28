<?php

declare (strict_types=1);
namespace Rector\PHPOffice\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/PHPOffice/PhpSpreadsheet/blob/master/docs/topics/migration-from-PHPExcel.md#conditionalgetcondition
 *
 * @see \Rector\PHPOffice\Tests\Rector\MethodCall\ChangeConditionalGetConditionRector\ChangeConditionalGetConditionRectorTest
 */
final class ChangeConditionalGetConditionRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change argument PHPExcel_Style_Conditional->getCondition() to getConditions()', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(): void
    {
        $conditional = new \PHPExcel_Style_Conditional;
        $someCondition = $conditional->getCondition();
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(): void
    {
        $conditional = new \PHPExcel_Style_Conditional;
        $someCondition = $conditional->getConditions()[0] ?? '';
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
        return [\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param MethodCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isOnClassMethodCall($node, 'PHPExcel_Style_Conditional', 'getCondition')) {
            return null;
        }
        $node->name = new \PhpParser\Node\Identifier('getConditions');
        $arrayDimFetch = new \PhpParser\Node\Expr\ArrayDimFetch($node, new \PhpParser\Node\Scalar\LNumber(0));
        return new \PhpParser\Node\Expr\BinaryOp\Coalesce($arrayDimFetch, new \PhpParser\Node\Scalar\String_(''));
    }
}
