<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\TypeDeclaration\Rector\ClassMethod;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Param;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod;
use _PhpScopere8e811afab72\PHPStan\Type\ArrayType;
use _PhpScopere8e811afab72\PHPStan\Type\MixedType;
use _PhpScopere8e811afab72\PHPStan\Type\Type;
use _PhpScopere8e811afab72\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Rector\TypeDeclaration\TypeInferer\ParamTypeInferer;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @sponsor Thanks https://spaceflow.io/ for sponsoring this rule - visit them on https://github.com/SpaceFlow-app
 *
 * @see \Rector\TypeDeclaration\Tests\Rector\ClassMethod\AddArrayParamDocTypeRector\AddArrayParamDocTypeRectorTest
 */
final class AddArrayParamDocTypeRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var ParamTypeInferer
     */
    private $paramTypeInferer;
    public function __construct(\_PhpScopere8e811afab72\Rector\TypeDeclaration\TypeInferer\ParamTypeInferer $paramTypeInferer)
    {
        $this->paramTypeInferer = $paramTypeInferer;
    }
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Adds @param annotation to array parameters inferred from the rest of the code', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var int[]
     */
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var int[]
     */
    private $values;

    /**
     * @param int[] $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
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
        return [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if ($node->getParams() === []) {
            return null;
        }
        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $node->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        foreach ($node->getParams() as $param) {
            if ($this->shouldSkipParam($param)) {
                return null;
            }
            $type = $this->paramTypeInferer->inferParam($param);
            if ($type instanceof \_PhpScopere8e811afab72\PHPStan\Type\MixedType) {
                return null;
            }
            $paramName = $this->getName($param);
            $phpDocInfo->changeParamType($type, $param, $paramName);
            return $node;
        }
    }
    private function shouldSkipParam(\_PhpScopere8e811afab72\PhpParser\Node\Param $param) : bool
    {
        // type missing at all
        if ($param->type === null) {
            return \true;
        }
        // not an array type
        if (!$this->isName($param->type, 'array')) {
            return \true;
        }
        // not an array type
        $paramStaticType = $this->getStaticType($param);
        if ($paramStaticType instanceof \_PhpScopere8e811afab72\PHPStan\Type\MixedType) {
            return \false;
        }
        if (!$paramStaticType instanceof \_PhpScopere8e811afab72\PHPStan\Type\ArrayType) {
            return \true;
        }
        if (!$paramStaticType->getIterableValueType() instanceof \_PhpScopere8e811afab72\PHPStan\Type\MixedType) {
            return \true;
        }
        // is defined mixed[] explicitly
        /** @var MixedType $mixedType */
        $mixedType = $paramStaticType->getIterableValueType();
        return $mixedType->isExplicitMixed();
    }
}
