<?php

declare (strict_types=1);
namespace RectorPrefix20210202;

use Rector\CodeQuality\Rector\Return_\SimplifyUselessVariableRector;
use Rector\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector;
use Rector\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector;
use Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\DeadCode\Rector\BinaryOp\RemoveDuplicatedInstanceOfRector;
use Rector\DeadCode\Rector\BooleanAnd\RemoveAndTrueRector;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;
use Rector\DeadCode\Rector\Class_\RemoveUnusedDoctrineEntityMethodAndPropertyRector;
use Rector\DeadCode\Rector\ClassConst\RemoveUnusedClassConstantRector;
use Rector\DeadCode\Rector\ClassConst\RemoveUnusedPrivateConstantRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveDeadConstructorRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveDeadRecursiveClassMethodRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveDelegatingParentCallRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedParameterRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DeadCode\Rector\Concat\RemoveConcatAutocastRector;
use Rector\DeadCode\Rector\Expression\RemoveDeadStmtRector;
use Rector\DeadCode\Rector\Expression\SimplifyMirrorAssignRector;
use Rector\DeadCode\Rector\For_\RemoveDeadIfForeachForRector;
use Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector;
use Rector\DeadCode\Rector\Function_\RemoveUnusedFunctionRector;
use Rector\DeadCode\Rector\FunctionLike\RemoveCodeAfterReturnRector;
use Rector\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector;
use Rector\DeadCode\Rector\FunctionLike\RemoveDuplicatedIfReturnRector;
use Rector\DeadCode\Rector\FunctionLike\RemoveOverriddenValuesRector;
use Rector\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector;
use Rector\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector;
use Rector\DeadCode\Rector\If_\UnwrapFutureCompatibleIfFunctionExistsRector;
use Rector\DeadCode\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector;
use Rector\DeadCode\Rector\MethodCall\RemoveDefaultArgumentValueRector;
use Rector\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector;
use Rector\DeadCode\Rector\Property\RemoveSetterOnlyPropertyAndMethodCallRector;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\DeadCode\Rector\PropertyProperty\RemoveNullPropertyInitializationRector;
use Rector\DeadCode\Rector\Return_\RemoveDeadConditionAboveReturnRector;
use Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector;
use Rector\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector;
use Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector;
use Rector\DeadCode\Rector\Ternary\TernaryToBooleanOrFalseToBooleanAndRector;
use Rector\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector;
use Rector\PHPUnit\Rector\ClassMethod\RemoveEmptyTestMethodRector;
use RectorPrefix20210202\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210202\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\DeadCode\Rector\If_\UnwrapFutureCompatibleIfFunctionExistsRector::class);
    $services->set(\Rector\DeadCode\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector::class);
    $services->set(\Rector\DeadCode\Rector\Cast\RecastingRemovalRector::class);
    $services->set(\Rector\DeadCode\Rector\Expression\RemoveDeadStmtRector::class);
    $services->set(\Rector\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector::class);
    $services->set(\Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector::class);
    $services->set(\Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector::class);
    $services->set(\Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector::class);
    $services->set(\Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector::class);
    $services->set(\Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector::class);
    $services->set(\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedParameterRector::class);
    $services->set(\Rector\DeadCode\Rector\Expression\SimplifyMirrorAssignRector::class);
    $services->set(\Rector\DeadCode\Rector\FunctionLike\RemoveOverriddenValuesRector::class);
    $services->set(\Rector\DeadCode\Rector\ClassConst\RemoveUnusedPrivateConstantRector::class);
    $services->set(\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector::class);
    $services->set(\Rector\DeadCode\Rector\FunctionLike\RemoveCodeAfterReturnRector::class);
    $services->set(\Rector\DeadCode\Rector\ClassMethod\RemoveDeadConstructorRector::class);
    $services->set(\Rector\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector::class);
    $services->set(\Rector\DeadCode\Rector\For_\RemoveDeadIfForeachForRector::class);
    $services->set(\Rector\DeadCode\Rector\BooleanAnd\RemoveAndTrueRector::class);
    $services->set(\Rector\DeadCode\Rector\MethodCall\RemoveDefaultArgumentValueRector::class);
    $services->set(\Rector\DeadCode\Rector\Concat\RemoveConcatAutocastRector::class);
    $services->set(\Rector\CodeQuality\Rector\Return_\SimplifyUselessVariableRector::class);
    $services->set(\Rector\DeadCode\Rector\ClassMethod\RemoveDelegatingParentCallRector::class);
    $services->set(\Rector\DeadCode\Rector\BinaryOp\RemoveDuplicatedInstanceOfRector::class);
    $services->set(\Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector::class);
    $services->set(\Rector\DeadCode\Rector\Class_\RemoveUnusedDoctrineEntityMethodAndPropertyRector::class);
    $services->set(\Rector\DeadCode\Rector\Property\RemoveSetterOnlyPropertyAndMethodCallRector::class);
    $services->set(\Rector\DeadCode\Rector\PropertyProperty\RemoveNullPropertyInitializationRector::class);
    $services->set(\Rector\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector::class);
    $services->set(\Rector\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector::class);
    $services->set(\Rector\DeadCode\Rector\Ternary\TernaryToBooleanOrFalseToBooleanAndRector::class);
    $services->set(\Rector\PHPUnit\Rector\ClassMethod\RemoveEmptyTestMethodRector::class);
    $services->set(\Rector\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector::class);
    $services->set(\Rector\DeadCode\Rector\ClassConst\RemoveUnusedClassConstantRector::class);
    $services->set(\Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector::class);
    $services->set(\Rector\DeadCode\Rector\FunctionLike\RemoveDuplicatedIfReturnRector::class);
    $services->set(\Rector\DeadCode\Rector\Function_\RemoveUnusedFunctionRector::class);
    $services->set(\Rector\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector::class);
    $services->set(\Rector\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector::class);
    $services->set(\Rector\DeadCode\Rector\ClassMethod\RemoveDeadRecursiveClassMethodRector::class);
    $services->set(\Rector\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector::class);
    $services->set(\Rector\DeadCode\Rector\Return_\RemoveDeadConditionAboveReturnRector::class);
};
