<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration\TypeInferer\ParamTypeInferer;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\PhpDocNode\PHPUnit\PHPUnitDataProviderTagValueNode;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\TypeDeclaration\Contract\TypeInferer\ParamTypeInfererInterface;
use PhpParser\Node\Expr\ArrayItem;
final class PHPUnitDataProviderParamTypeInferer implements \Rector\TypeDeclaration\Contract\TypeInferer\ParamTypeInfererInterface
{
    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;
    /**
     * @var NodeTypeResolver
     */
    private $nodeTypeResolver;
    /**
     * @var TypeFactory
     */
    private $typeFactory;
    /**
     * @var PhpDocInfoFactory
     */
    private $phpDocInfoFactory;
    public function __construct(\Rector\Core\PhpParser\Node\BetterNodeFinder $betterNodeFinder, \Rector\NodeTypeResolver\PHPStan\Type\TypeFactory $typeFactory, \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory $phpDocInfoFactory)
    {
        $this->betterNodeFinder = $betterNodeFinder;
        $this->typeFactory = $typeFactory;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
    }
    /**
     * Prevents circular reference
     * @required
     */
    public function autowirePHPUnitDataProviderParamTypeInferer(\Rector\NodeTypeResolver\NodeTypeResolver $nodeTypeResolver) : void
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
    }
    public function inferParam(\PhpParser\Node\Param $param) : \PHPStan\Type\Type
    {
        $dataProviderClassMethod = $this->resolveDataProviderClassMethod($param);
        if (!$dataProviderClassMethod instanceof \PhpParser\Node\Stmt\ClassMethod) {
            return new \PHPStan\Type\MixedType();
        }
        $parameterPosition = $param->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARAMETER_POSITION);
        if ($parameterPosition === null) {
            return new \PHPStan\Type\MixedType();
        }
        /** @var Return_[] $returns */
        $returns = $this->betterNodeFinder->findInstanceOf((array) $dataProviderClassMethod->stmts, \PhpParser\Node\Stmt\Return_::class);
        if ($returns !== []) {
            return $this->resolveReturnStaticArrayTypeByParameterPosition($returns, $parameterPosition);
        }
        /** @var Yield_[] $yields */
        $yields = $this->betterNodeFinder->findInstanceOf((array) $dataProviderClassMethod->stmts, \PhpParser\Node\Expr\Yield_::class);
        return $this->resolveYieldStaticArrayTypeByParameterPosition($yields, $parameterPosition);
    }
    private function resolveDataProviderClassMethod(\PhpParser\Node\Param $param) : ?\PhpParser\Node\Stmt\ClassMethod
    {
        $phpDocInfo = $this->getFunctionLikePhpDocInfo($param);
        $phpUnitDataProviderTagValueNode = $phpDocInfo->getByType(\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\PHPUnit\PHPUnitDataProviderTagValueNode::class);
        if (!$phpUnitDataProviderTagValueNode instanceof \Rector\BetterPhpDocParser\ValueObject\PhpDocNode\PHPUnit\PHPUnitDataProviderTagValueNode) {
            return null;
        }
        $classLike = $param->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
            return null;
        }
        return $classLike->getMethod($phpUnitDataProviderTagValueNode->getMethodName());
    }
    /**
     * @param Return_[] $returns
     */
    private function resolveReturnStaticArrayTypeByParameterPosition(array $returns, int $parameterPosition) : \PHPStan\Type\Type
    {
        $paramOnPositionTypes = [];
        if (!$returns[0]->expr instanceof \PhpParser\Node\Expr\Array_) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        foreach ($returns[0]->expr->items as $singleDataProvidedSet) {
            if (!$singleDataProvidedSet instanceof \PhpParser\Node\Expr\ArrayItem || !$singleDataProvidedSet->value instanceof \PhpParser\Node\Expr\Array_) {
                throw new \Rector\Core\Exception\ShouldNotHappenException();
            }
            foreach ($singleDataProvidedSet->value->items as $position => $singleDataProvidedSetItem) {
                if ($position !== $parameterPosition || !$singleDataProvidedSetItem instanceof \PhpParser\Node\Expr\ArrayItem) {
                    continue;
                }
                $paramOnPositionTypes[] = $this->nodeTypeResolver->resolve($singleDataProvidedSetItem->value);
            }
        }
        if ($paramOnPositionTypes === []) {
            return new \PHPStan\Type\MixedType();
        }
        return $this->typeFactory->createMixedPassedOrUnionType($paramOnPositionTypes);
    }
    /**
     * @param Yield_[] $yields
     */
    private function resolveYieldStaticArrayTypeByParameterPosition(array $yields, int $parameterPosition) : \PHPStan\Type\Type
    {
        $paramOnPositionTypes = [];
        foreach ($yields as $classMethodYield) {
            if (!$classMethodYield->value instanceof \PhpParser\Node\Expr\Array_) {
                continue;
            }
            $type = $this->getTypeFromClassMethodYield($classMethodYield->value);
            if (!$type instanceof \PHPStan\Type\Constant\ConstantArrayType) {
                return $type;
            }
            foreach ($type->getValueTypes() as $position => $valueType) {
                if ($position !== $parameterPosition) {
                    continue;
                }
                $paramOnPositionTypes[] = $valueType;
            }
        }
        if ($paramOnPositionTypes === []) {
            return new \PHPStan\Type\MixedType();
        }
        return $this->typeFactory->createMixedPassedOrUnionType($paramOnPositionTypes);
    }
    private function getTypeFromClassMethodYield(\PhpParser\Node\Expr\Array_ $classMethodYieldArrayNode) : \PHPStan\Type\Type
    {
        $arrayTypes = $this->nodeTypeResolver->resolve($classMethodYieldArrayNode);
        // impossible to resolve
        if (!$arrayTypes instanceof \PHPStan\Type\Constant\ConstantArrayType) {
            return new \PHPStan\Type\MixedType();
        }
        return $arrayTypes;
    }
    private function getFunctionLikePhpDocInfo(\PhpParser\Node\Param $param) : \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo
    {
        $parent = $param->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$parent instanceof \PhpParser\Node\FunctionLike) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        return $this->phpDocInfoFactory->createFromNodeOrEmpty($parent);
    }
}
