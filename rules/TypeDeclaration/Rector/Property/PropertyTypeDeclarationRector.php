<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\MixedType;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Rector\AbstractRector;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Property\PropertyTypeDeclarationRector\PropertyTypeDeclarationRectorTest
 */
final class PropertyTypeDeclarationRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var PropertyTypeInferer
     */
    private $propertyTypeInferer;
    /**
     * @var PhpDocTypeChanger
     */
    private $phpDocTypeChanger;
    /**
     * @param \Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer $propertyTypeInferer
     * @param \Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger $phpDocTypeChanger
     */
    public function __construct($propertyTypeInferer, $phpDocTypeChanger)
    {
        $this->propertyTypeInferer = $propertyTypeInferer;
        $this->phpDocTypeChanger = $phpDocTypeChanger;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Add @var to properties that are missing it', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    private $value;

    public function run()
    {
        $this->value = 123;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var int
     */
    private $value;

    public function run()
    {
        $this->value = 123;
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Property::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (\count($node->props) !== 1) {
            return null;
        }
        if ($node->type !== null) {
            return null;
        }
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        // is already set
        if (!$phpDocInfo->getVarType() instanceof \PHPStan\Type\MixedType) {
            return null;
        }
        $type = $this->propertyTypeInferer->inferProperty($node);
        if ($type instanceof \PHPStan\Type\MixedType) {
            return null;
        }
        $this->phpDocTypeChanger->changeVarType($phpDocInfo, $type);
        return $node;
    }
}
