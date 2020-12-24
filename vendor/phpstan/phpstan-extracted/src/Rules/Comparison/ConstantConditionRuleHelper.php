<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PHPStan\Rules\Comparison;

use _PhpScoperb75b35f52b74\PhpParser\Node\Expr;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall;
use _PhpScoperb75b35f52b74\PHPStan\Analyser\Scope;
use _PhpScoperb75b35f52b74\PHPStan\Type\BooleanType;
class ConstantConditionRuleHelper
{
    /** @var ImpossibleCheckTypeHelper */
    private $impossibleCheckTypeHelper;
    /** @var bool */
    private $treatPhpDocTypesAsCertain;
    public function __construct(\_PhpScoperb75b35f52b74\PHPStan\Rules\Comparison\ImpossibleCheckTypeHelper $impossibleCheckTypeHelper, bool $treatPhpDocTypesAsCertain)
    {
        $this->impossibleCheckTypeHelper = $impossibleCheckTypeHelper;
        $this->treatPhpDocTypesAsCertain = $treatPhpDocTypesAsCertain;
    }
    public function shouldReportAlwaysTrueByDefault(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr $expr) : bool
    {
        return $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BooleanNot || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BooleanOr || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BooleanAnd || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Ternary || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Isset_;
    }
    public function shouldSkip(\_PhpScoperb75b35f52b74\PHPStan\Analyser\Scope $scope, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr $expr) : bool
    {
        if ($expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Instanceof_ || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\Identical || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\NotIdentical || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BooleanNot || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BooleanOr || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BooleanAnd || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Ternary || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Isset_ || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\Greater || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\GreaterOrEqual || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\Smaller || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\SmallerOrEqual) {
            // already checked by different rules
            return \true;
        }
        if ($expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall || $expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall) {
            $isAlways = $this->impossibleCheckTypeHelper->findSpecifiedType($scope, $expr);
            if ($isAlways !== null) {
                return \true;
            }
        }
        return \false;
    }
    public function getBooleanType(\_PhpScoperb75b35f52b74\PHPStan\Analyser\Scope $scope, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr $expr) : \_PhpScoperb75b35f52b74\PHPStan\Type\BooleanType
    {
        if ($this->shouldSkip($scope, $expr)) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Type\BooleanType();
        }
        if ($this->treatPhpDocTypesAsCertain) {
            return $scope->getType($expr)->toBoolean();
        }
        return $scope->getNativeType($expr)->toBoolean();
    }
    public function getNativeBooleanType(\_PhpScoperb75b35f52b74\PHPStan\Analyser\Scope $scope, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr $expr) : \_PhpScoperb75b35f52b74\PHPStan\Type\BooleanType
    {
        if ($this->shouldSkip($scope, $expr)) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Type\BooleanType();
        }
        return $scope->getNativeType($expr)->toBoolean();
    }
}
