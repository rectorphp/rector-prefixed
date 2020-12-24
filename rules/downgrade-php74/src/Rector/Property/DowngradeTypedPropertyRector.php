<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\DowngradePhp74\Rector\Property;

use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DowngradePhp74\Tests\Rector\Property\DowngradeTypedPropertyRector\DowngradeTypedPropertyRectorTest
 */
final class DowngradeTypedPropertyRector extends \_PhpScoperb75b35f52b74\Rector\DowngradePhp74\Rector\Property\AbstractDowngradeTypedPropertyRector
{
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes property type definition from type definitions to `@var` annotations.', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    private string $property;
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
    * @var string
    */
    private $property;
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property::class];
    }
    public function shouldRemoveProperty(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property $property) : bool
    {
        return \true;
    }
}
