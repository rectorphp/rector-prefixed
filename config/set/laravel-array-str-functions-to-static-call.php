<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72;

use _PhpScopere8e811afab72\Rector\Generic\Rector\FuncCall\FuncCallToStaticCallRector;
use _PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall;
use _PhpScopere8e811afab72\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use _PhpScopere8e811afab72\Symplify\SymfonyPhpConfig\ValueObjectInliner;
// @see https://medium.freecodecamp.org/moving-away-from-magic-or-why-i-dont-want-to-use-laravel-anymore-2ce098c979bd
// @see https://laravel.com/docs/5.7/facades#facades-vs-dependency-injection
return static function (\_PhpScopere8e811afab72\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScopere8e811afab72\Rector\Generic\Rector\FuncCall\FuncCallToStaticCallRector::class)->call('configure', [[\_PhpScopere8e811afab72\Rector\Generic\Rector\FuncCall\FuncCallToStaticCallRector::FUNC_CALLS_TO_STATIC_CALLS => \_PhpScopere8e811afab72\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_add', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'add'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_collapse', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'collapse'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_divide', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'divide'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_dot', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'dot'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_except', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'except'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_first', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'first'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_flatten', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'flatten'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_forget', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'forget'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_get', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'get'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_has', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'has'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_last', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'last'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_only', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'only'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_pluck', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'pluck'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_prepend', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'prepend'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_pull', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'pull'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_random', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'random'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_sort', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'sort'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_sort_recursive', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'sortRecursive'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_where', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'where'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_wrap', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'wrap'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('array_set', '_PhpScopere8e811afab72\\Illuminate\\Support\\Arr', 'set'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('camel_case', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'camel'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('ends_with', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'endsWith'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('kebab_case', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'kebab'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('snake_case', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'snake'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('starts_with', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'startsWith'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_after', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'after'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_before', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'before'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_contains', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'contains'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_finish', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'finish'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_is', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'is'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_limit', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'limit'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_plural', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'plural'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_random', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'random'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_replace_array', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'replaceArray'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_replace_first', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'replaceFirst'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_replace_last', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'replaceLast'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_singular', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'singular'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_slug', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'slug'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('str_start', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'start'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('studly_case', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'studly'), new \_PhpScopere8e811afab72\Rector\Transform\ValueObject\FuncCallToStaticCall('title_case', '_PhpScopere8e811afab72\\Illuminate\\Support\\Str', 'title')])]]);
};
