<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72;

use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Array_\ArrayThisCallToThisMethodCallRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Assign\SplitListAssignToSeparateLineRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Concat\JoinStringConcatRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\For_\ForToForeachRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Foreach_\ForeachToInArrayRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\AddPregQuoteDelimiterRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\ArrayKeysAndInArrayToArrayKeyExistsRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\ArrayMergeOfNonArraysToSimpleArrayRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\ChangeArrayPushToArrayAssignRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\InArrayAndArrayKeysToArrayKeyExistsRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\IntvalToTypeCastRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\IsAWithStringWithThirdArgumentRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\RemoveSoleValueSprintfRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SetTypeToCastRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SimplifyFuncGetArgsCountRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SimplifyInArrayValuesRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SimplifyStrposLowerRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\UnwrapSprintfOneArgumentRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\FunctionLike\RemoveAlwaysTrueConditionSetInConstructorRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\SimplifyArraySearchRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\SimplifyConditionsRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\CombineIfRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\ShortenElseIfRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\SimplifyIfIssetToNullCoalescingRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\SimplifyIfNotNullReturnRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Include_\AbsolutizeRequireAndIncludePathRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\LogicalAnd\AndAssignsToSeparateLinesRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Name\FixClassCaseSensitivityNameRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\New_\NewStaticToNewSelfRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\NotEqual\CommonNotEqualRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Return_\SimplifyUselessVariableRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Ternary\SimplifyDuplicatedTernaryRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Ternary\SimplifyTautologyTernaryRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector;
use _PhpScopere8e811afab72\Rector\CodeQuality\Rector\Ternary\UnnecessaryTernaryExpressionRector;
use _PhpScopere8e811afab72\Rector\Php52\Rector\Property\VarToPublicPropertyRector;
use _PhpScopere8e811afab72\Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use _PhpScopere8e811afab72\Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use _PhpScopere8e811afab72\Rector\SOLID\Rector\ClassMethod\UseInterfaceOverImplementationInConstructorRector;
use _PhpScopere8e811afab72\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\_PhpScopere8e811afab72\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Assign\CombinedAssignRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Foreach_\ForeachToInArrayRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\InArrayAndArrayKeysToArrayKeyExistsRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SimplifyFuncGetArgsCountRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SimplifyInArrayValuesRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SimplifyStrposLowerRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\SimplifyArraySearchRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\SimplifyConditionsRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\SimplifyIfNotNullReturnRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Return_\SimplifyUselessVariableRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Ternary\UnnecessaryTernaryExpressionRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Ternary\SimplifyTautologyTernaryRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Concat\JoinStringConcatRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\SimplifyIfIssetToNullCoalescingRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\CombineIfRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Ternary\SimplifyDuplicatedTernaryRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\LogicalAnd\AndAssignsToSeparateLinesRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\For_\ForToForeachRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\IsAWithStringWithThirdArgumentRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FunctionLike\RemoveAlwaysTrueConditionSetInConstructorRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\RemoveSoleValueSprintfRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\If_\ShortenElseIfRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\SOLID\Rector\ClassMethod\UseInterfaceOverImplementationInConstructorRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\AddPregQuoteDelimiterRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\ArrayMergeOfNonArraysToSimpleArrayRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\IntvalToTypeCastRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Include_\AbsolutizeRequireAndIncludePathRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\ChangeArrayPushToArrayAssignRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\ArrayKeysAndInArrayToArrayKeyExistsRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Assign\SplitListAssignToSeparateLineRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Array_\ArrayThisCallToThisMethodCallRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\NotEqual\CommonNotEqualRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\Renaming\Rector\FuncCall\RenameFunctionRector::class)->call('configure', [[\_PhpScopere8e811afab72\Rector\Renaming\Rector\FuncCall\RenameFunctionRector::OLD_FUNCTION_TO_NEW_FUNCTION => [
        'split' => 'explode',
        'join' => 'implode',
        'sizeof' => 'count',
        # https://www.php.net/manual/en/aliases.php
        'chop' => 'rtrim',
        'doubleval' => 'floatval',
        'gzputs' => 'gzwrites',
        'fputs' => 'fwrite',
        'ini_alter' => 'ini_set',
        'is_double' => 'is_float',
        'is_integer' => 'is_int',
        'is_long' => 'is_int',
        'is_real' => 'is_float',
        'is_writeable' => 'is_writable',
        'key_exists' => 'array_key_exists',
        'pos' => 'current',
        'strchr' => 'strstr',
        # mb
        'mbstrcut' => 'mb_strcut',
        'mbstrlen' => 'mb_strlen',
        'mbstrpos' => 'mb_strpos',
        'mbstrrpos' => 'mb_strrpos',
        'mbsubstr' => 'mb_substr',
    ]]]);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\SetTypeToCastRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\Php52\Rector\Property\VarToPublicPropertyRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Name\FixClassCaseSensitivityNameRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\New_\NewStaticToNewSelfRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\FuncCall\UnwrapSprintfOneArgumentRector::class);
    $services->set(\_PhpScopere8e811afab72\Rector\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector::class);
};
