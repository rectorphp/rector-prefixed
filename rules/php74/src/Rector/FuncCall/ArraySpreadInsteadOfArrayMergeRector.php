<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Php74\Rector\FuncCall;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Array_;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayItem;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Ternary;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Variable;
use _PhpScopere8e811afab72\PHPStan\Type\ArrayType;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantArrayType;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantStringType;
use _PhpScopere8e811afab72\PHPStan\Type\IntegerType;
use _PhpScopere8e811afab72\PHPStan\Type\Type;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\Core\ValueObject\PhpVersionFeature;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://wiki.php.net/rfc/spread_operator_for_array
 * @see https://twitter.com/nikita_ppv/status/1126470222838366209
 * @see \Rector\Php74\Tests\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\ArraySpreadInsteadOfArrayMergeRectorTest
 */
final class ArraySpreadInsteadOfArrayMergeRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change array_merge() to spread operator, except values with possible string key values', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run($iter1, $iter2)
    {
        $values = array_merge(iterator_to_array($iter1), iterator_to_array($iter2));

        // Or to generalize to all iterables
        $anotherValues = array_merge(
            is_array($iter1) ? $iter1 : iterator_to_array($iter1),
            is_array($iter2) ? $iter2 : iterator_to_array($iter2)
        );
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($iter1, $iter2)
    {
        $values = [...$iter1, ...$iter2];

        // Or to generalize to all iterables
        $anotherValues = [...$iter1, ...$iter2];
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
        return [\_PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param FuncCall $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if (!$this->isAtLeastPhpVersion(\_PhpScopere8e811afab72\Rector\Core\ValueObject\PhpVersionFeature::ARRAY_SPREAD)) {
            return null;
        }
        if ($this->isName($node, 'array_merge')) {
            return $this->refactorArray($node);
        }
        return null;
    }
    private function refactorArray(\_PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall $funcCall) : ?\_PhpScopere8e811afab72\PhpParser\Node\Expr\Array_
    {
        $array = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Array_();
        foreach ($funcCall->args as $arg) {
            // cannot handle unpacked arguments
            if ($arg->unpack) {
                return null;
            }
            $value = $arg->value;
            if ($this->shouldSkipArrayForInvalidTypeOrKeys($value)) {
                return null;
            }
            $value = $this->resolveValue($value);
            $array->items[] = $this->createUnpackedArrayItem($value);
        }
        return $array;
    }
    private function shouldSkipArrayForInvalidTypeOrKeys(\_PhpScopere8e811afab72\PhpParser\Node\Expr $expr) : bool
    {
        // we have no idea what it is → cannot change it
        if (!$this->isArrayType($expr)) {
            return \true;
        }
        $arrayStaticType = $this->getStaticType($expr);
        if ($this->isConstantArrayTypeWithStringKeyType($arrayStaticType)) {
            return \true;
        }
        if (!$arrayStaticType instanceof \_PhpScopere8e811afab72\PHPStan\Type\ArrayType) {
            return \true;
        }
        // integer key type is required, @see https://twitter.com/nikita_ppv/status/1126470222838366209
        return !$arrayStaticType->getKeyType() instanceof \_PhpScopere8e811afab72\PHPStan\Type\IntegerType;
    }
    private function resolveValue(\_PhpScopere8e811afab72\PhpParser\Node\Expr $expr) : \_PhpScopere8e811afab72\PhpParser\Node\Expr
    {
        if ($this->isIteratorToArrayFuncCall($expr)) {
            /** @var FuncCall $expr */
            $expr = $expr->args[0]->value;
        }
        if (!$expr instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Ternary) {
            return $expr;
        }
        if (!$expr->cond instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall) {
            return $expr;
        }
        if (!$this->isName($expr->cond, 'is_array')) {
            return $expr;
        }
        if ($expr->if instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable && $this->isIteratorToArrayFuncCall($expr->else)) {
            return $expr->if;
        }
        return $expr;
    }
    private function createUnpackedArrayItem(\_PhpScopere8e811afab72\PhpParser\Node\Expr $expr) : \_PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayItem
    {
        return new \_PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayItem($expr, null, \false, [], \true);
    }
    private function isConstantArrayTypeWithStringKeyType(\_PhpScopere8e811afab72\PHPStan\Type\Type $type) : bool
    {
        if (!$type instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantArrayType) {
            return \false;
        }
        foreach ($type->getKeyTypes() as $keyType) {
            // key cannot be string
            if ($keyType instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantStringType) {
                return \true;
            }
        }
        return \false;
    }
    private function isIteratorToArrayFuncCall(\_PhpScopere8e811afab72\PhpParser\Node\Expr $expr) : bool
    {
        return $this->isFuncCallName($expr, 'iterator_to_array');
    }
}
