<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\PHPOffice\Rector\StaticCall;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall;
use _PhpScopere8e811afab72\PhpParser\Node\Scalar\String_;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/PHPOffice/PhpSpreadsheet/blob/master/docs/topics/migration-from-PHPExcel.md#renamed-readers-and-writers
 *
 * @see \Rector\PHPOffice\Tests\Rector\StaticCall\ChangeIOFactoryArgumentRector\ChangeIOFactoryArgumentRectorTest
 */
final class ChangeIOFactoryArgumentRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string[]
     */
    private const OLD_TO_NEW_TYPE = ['CSV' => 'Csv', 'Excel2003XML' => 'Xml', 'Excel2007' => 'Xlsx', 'Excel5' => 'Xls', 'Gnumeric' => 'Gnumeric', 'HTML' => 'Html', 'OOCalc' => 'Ods', 'OpenDocument' => 'Ods', 'PDF' => 'Pdf', 'SYLK' => 'Slk'];
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change argument of PHPExcel_IOFactory::createReader(), PHPExcel_IOFactory::createWriter() and PHPExcel_IOFactory::identify()', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(): void
    {
        $writer = \PHPExcel_IOFactory::createWriter('CSV');
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(): void
    {
        $writer = \PHPExcel_IOFactory::createWriter('Csv');
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
        return [\_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param StaticCall $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if (!$this->isStaticCallsNamed($node, 'PHPExcel_IOFactory', ['createReader', 'createWriter', 'identify'])) {
            return null;
        }
        $firstArgumentValue = $this->getValue($node->args[0]->value);
        $newValue = self::OLD_TO_NEW_TYPE[$firstArgumentValue] ?? null;
        if ($newValue === null) {
            return null;
        }
        $node->args[0]->value = new \_PhpScopere8e811afab72\PhpParser\Node\Scalar\String_($newValue);
        return $node;
    }
}
