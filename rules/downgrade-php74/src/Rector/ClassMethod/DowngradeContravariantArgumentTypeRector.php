<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\DowngradePhp74\Rector\ClassMethod;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\FunctionLike;
use _PhpScoperb75b35f52b74\PhpParser\Node\NullableType;
use _PhpScoperb75b35f52b74\PhpParser\Node\Param;
use _PhpScoperb75b35f52b74\PhpParser\Node\UnionType;
use _PhpScoperb75b35f52b74\PHPStan\Analyser\Scope;
use _PhpScoperb75b35f52b74\PHPStan\Reflection\ClassReflection;
use _PhpScoperb75b35f52b74\Rector\DowngradePhp70\Rector\FunctionLike\AbstractDowngradeParamDeclarationRector;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://www.php.net/manual/en/language.oop5.variance.php#language.oop5.variance.contravariance
 *
 * @see \Rector\DowngradePhp74\Tests\Rector\ClassMethod\DowngradeContravariantArgumentTypeRector\DowngradeContravariantArgumentTypeRectorTest
 */
final class DowngradeContravariantArgumentTypeRector extends \_PhpScoperb75b35f52b74\Rector\DowngradePhp70\Rector\FunctionLike\AbstractDowngradeParamDeclarationRector
{
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove contravariant argument type declarations', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class ParentType {}
class ChildType extends ParentType {}

class A
{
    public function contraVariantArguments(ChildType $type)
    { /* … */ }
}

class B extends A
{
    public function contraVariantArguments(ParentType $type)
    { /* … */ }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class ParentType {}
class ChildType extends ParentType {}

class A
{
    public function contraVariantArguments(ChildType $type)
    { /* … */ }
}

class B extends A
{
    /**
     * @param ParentType $type
     */
    public function contraVariantArguments($type)
    { /* … */ }
}
CODE_SAMPLE
)]);
    }
    public function shouldRemoveParamDeclaration(\_PhpScoperb75b35f52b74\PhpParser\Node\Param $param, \_PhpScoperb75b35f52b74\PhpParser\Node\FunctionLike $functionLike) : bool
    {
        if ($param->variadic) {
            return \false;
        }
        if ($param->type === null) {
            return \false;
        }
        // Don't consider for Union types
        if ($param->type instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\UnionType) {
            return \false;
        }
        // Check if the type is different from the one declared in some ancestor
        return $this->getDifferentParamTypeFromAncestorClass($param, $functionLike) !== null;
    }
    private function getDifferentParamTypeFromAncestorClass(\_PhpScoperb75b35f52b74\PhpParser\Node\Param $param, \_PhpScoperb75b35f52b74\PhpParser\Node\FunctionLike $functionLike) : ?string
    {
        /** @var Scope|null $scope */
        $scope = $functionLike->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE);
        if ($scope === null) {
            // possibly trait
            return null;
        }
        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return null;
        }
        $paramName = $this->getName($param);
        // If it is the NullableType, extract the name from its inner type
        /** @var Node */
        $paramType = $param->type;
        $isNullableType = $param->type instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\NullableType;
        if ($isNullableType) {
            /** @var NullableType */
            $nullableType = $paramType;
            $paramTypeName = $this->getName($nullableType->type);
        } else {
            $paramTypeName = $this->getName($paramType);
        }
        if ($paramTypeName === null) {
            return null;
        }
        /** @var string $methodName */
        $methodName = $this->getName($functionLike);
        // Either Ancestor classes or implemented interfaces
        $parentClassNames = \array_merge($classReflection->getParentClassesNames(), \array_map(function (\_PhpScoperb75b35f52b74\PHPStan\Reflection\ClassReflection $interfaceReflection) : string {
            return $interfaceReflection->getName();
        }, $classReflection->getInterfaces()));
        foreach ($parentClassNames as $parentClassName) {
            if (!\method_exists($parentClassName, $methodName)) {
                continue;
            }
            // Find the param we're looking for
            $parentReflectionMethod = new \ReflectionMethod($parentClassName, $methodName);
            $differentAncestorParamTypeName = $this->getDifferentParamTypeFromReflectionMethod($parentReflectionMethod, $paramName, $paramTypeName);
            if ($differentAncestorParamTypeName !== null) {
                return $differentAncestorParamTypeName;
            }
        }
        return null;
    }
    private function getDifferentParamTypeFromReflectionMethod(\ReflectionMethod $parentReflectionMethod, string $paramName, string $paramTypeName) : ?string
    {
        /** @var ReflectionParameter[] */
        $parentReflectionMethodParams = $parentReflectionMethod->getParameters();
        foreach ($parentReflectionMethodParams as $reflectionParameter) {
            if ($reflectionParameter->name === $paramName) {
                /**
                 * Getting a ReflectionNamedType works from PHP 7.1 onwards
                 * @see https://www.php.net/manual/en/reflectionparameter.gettype.php#125334
                 */
                /** @var ReflectionNamedType|null */
                $reflectionParamType = $reflectionParameter->getType();
                /**
                 * If the type is null, we don't have enough information
                 * to check if they are different. Then do nothing
                 */
                if ($reflectionParamType === null) {
                    continue;
                }
                if ($reflectionParamType->getName() !== $paramTypeName) {
                    // We found it: a different param type in some ancestor
                    return $reflectionParamType->getName();
                }
            }
        }
        return null;
    }
}
