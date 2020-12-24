<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74;

use _PhpScoperb75b35f52b74\Rector\CodeQuality\Rector\Return_\SimplifyUselessVariableRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\BinaryOp\RemoveDuplicatedInstanceOfRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\BooleanAnd\RemoveAndTrueRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Class_\RemoveUnusedDoctrineEntityMethodAndPropertyRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassConst\RemoveUnusedClassConstantRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassConst\RemoveUnusedPrivateConstantRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveDeadConstructorRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveDeadRecursiveClassMethodRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveDelegatingParentCallRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedParameterRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Concat\RemoveConcatAutocastRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Expression\RemoveDeadStmtRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Expression\SimplifyMirrorAssignRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\For_\RemoveDeadIfForeachForRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Function_\RemoveUnusedFunctionRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\FunctionLike\RemoveCodeAfterReturnRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\FunctionLike\RemoveDuplicatedIfReturnRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\FunctionLike\RemoveOverriddenValuesRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\MethodCall\RemoveDefaultArgumentValueRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Property\RemoveSetterOnlyPropertyAndMethodCallRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\PropertyProperty\RemoveNullPropertyInitializationRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Ternary\TernaryToBooleanOrFalseToBooleanAndRector;
use _PhpScoperb75b35f52b74\Rector\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\ClassMethod\RemoveEmptyTestMethodRector;
use _PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\_PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Expression\RemoveDeadStmtRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedParameterRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Expression\SimplifyMirrorAssignRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\FunctionLike\RemoveOverriddenValuesRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassConst\RemoveUnusedPrivateConstantRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\FunctionLike\RemoveCodeAfterReturnRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveDeadConstructorRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\For_\RemoveDeadIfForeachForRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\BooleanAnd\RemoveAndTrueRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\MethodCall\RemoveDefaultArgumentValueRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Concat\RemoveConcatAutocastRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\CodeQuality\Rector\Return_\SimplifyUselessVariableRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveDelegatingParentCallRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\BinaryOp\RemoveDuplicatedInstanceOfRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Class_\RemoveUnusedDoctrineEntityMethodAndPropertyRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Property\RemoveSetterOnlyPropertyAndMethodCallRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\PropertyProperty\RemoveNullPropertyInitializationRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Ternary\TernaryToBooleanOrFalseToBooleanAndRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\ClassMethod\RemoveEmptyTestMethodRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassConst\RemoveUnusedClassConstantRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\FunctionLike\RemoveDuplicatedIfReturnRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Function_\RemoveUnusedFunctionRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\ClassMethod\RemoveDeadRecursiveClassMethodRector::class);
    $services->set(\_PhpScoperb75b35f52b74\Rector\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector::class);
};
