<?php

declare (strict_types=1);
namespace Rector\DowngradePhp80\Rector\FunctionLike;

use PHPStan\Type\MixedType;
use Rector\DowngradePhp72\Rector\FunctionLike\AbstractDowngradeReturnTypeDeclarationRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DowngradePhp80\Tests\Rector\FunctionLike\DowngradeReturnMixedTypeDeclarationRector\DowngradeReturnMixedTypeDeclarationRectorTest
 */
final class DowngradeReturnMixedTypeDeclarationRector extends \Rector\DowngradePhp72\Rector\FunctionLike\AbstractDowngradeReturnTypeDeclarationRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove "mixed" return type, add a "@return mixed" tag instead', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function getAnything(bool $flag): mixed
    {
        if ($flag) {
            return 1;
        }
        return 'Hello world'
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return mixed
     */
    public function getAnything(bool $flag)
    {
        if ($flag) {
            return 1;
        }
        return 'Hello world'
    }
}
CODE_SAMPLE
)]);
    }
    public function getTypeToRemove() : string
    {
        return \PHPStan\Type\MixedType::class;
    }
}
