<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\TypeDeclaration\Rector\Property;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property;
use _PhpScoperb75b35f52b74\PHPStan\Type\MixedType;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoperb75b35f52b74\Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @sponsor Thanks https://spaceflow.io/ for sponsoring this rule - visit them on https://github.com/SpaceFlow-app
 *
 * @see \Rector\TypeDeclaration\Tests\Rector\Property\PropertyTypeDeclarationRector\PropertyTypeDeclarationRectorTest
 */
final class PropertyTypeDeclarationRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @var PropertyTypeInferer
     */
    private $propertyTypeInferer;
    public function __construct(\_PhpScoperb75b35f52b74\Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer $propertyTypeInferer)
    {
        $this->propertyTypeInferer = $propertyTypeInferer;
    }
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Add @var to properties that are missing it', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
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
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property::class];
    }
    /**
     * @param Property $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if (\count((array) $node->props) !== 1) {
            return null;
        }
        if ($node->type !== null) {
            return null;
        }
        // is already set
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $node->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo !== null && !$phpDocInfo->getVarType() instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\MixedType) {
            return null;
        }
        $type = $this->propertyTypeInferer->inferProperty($node);
        if ($type instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\MixedType) {
            return null;
        }
        if ($phpDocInfo === null) {
            $phpDocInfo = $this->phpDocInfoFactory->createEmpty($node);
        }
        $phpDocInfo->changeVarType($type);
        return $node;
    }
}
