<?php

declare (strict_types=1);
namespace Rector\DowngradePhp80\Rector\FunctionLike;

use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\UnionType;
use Rector\DowngradePhp70\Rector\FunctionLike\AbstractDowngradeParamDeclarationRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DowngradePhp80\Tests\Rector\FunctionLike\DowngradeUnionTypeParamDeclarationRector\DowngradeUnionTypeParamDeclarationRectorTest
 *
 * @requires PHP 8.0
 */
final class DowngradeUnionTypeParamDeclarationRector extends \Rector\DowngradePhp70\Rector\FunctionLike\AbstractDowngradeParamDeclarationRector
{
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove the union type params, add @param tags instead', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function echoInput(string|int $input)
    {
        echo $input;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @param string|int $input
     */
    public function echoInput($input)
    {
        echo $input;
    }
}
CODE_SAMPLE
)]);
    }
    public function shouldRemoveParamDeclaration(\PhpParser\Node\Param $param, \PhpParser\Node\FunctionLike $functionLike) : bool
    {
        if ($param->variadic) {
            return \false;
        }
        if ($param->type === null) {
            return \false;
        }
        // Check it is the union type
        return $param->type instanceof \PhpParser\Node\UnionType;
    }
}
