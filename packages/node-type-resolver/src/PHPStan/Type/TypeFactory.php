<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\NodeTypeResolver\PHPStan\Type;

use _PhpScopere8e811afab72\PHPStan\Type\BooleanType;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantBooleanType;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantFloatType;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantIntegerType;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantStringType;
use _PhpScopere8e811afab72\PHPStan\Type\FloatType;
use _PhpScopere8e811afab72\PHPStan\Type\IntegerType;
use _PhpScopere8e811afab72\PHPStan\Type\MixedType;
use _PhpScopere8e811afab72\PHPStan\Type\ObjectType;
use _PhpScopere8e811afab72\PHPStan\Type\StringType;
use _PhpScopere8e811afab72\PHPStan\Type\Type;
use _PhpScopere8e811afab72\PHPStan\Type\UnionType;
use _PhpScopere8e811afab72\PHPStan\Type\VerbosityLevel;
use _PhpScopere8e811afab72\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScopere8e811afab72\Rector\PHPStan\Type\FullyQualifiedObjectType;
use _PhpScopere8e811afab72\Rector\PHPStan\Type\ShortenedObjectType;
use _PhpScopere8e811afab72\Rector\PHPStan\TypeFactoryStaticHelper;
final class TypeFactory
{
    /**
     * @param Type[] $types
     */
    public function createMixedPassedOrUnionTypeAndKeepConstant(array $types) : \_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        $types = $this->unwrapUnionedTypes($types);
        $types = $this->uniquateTypes($types, \true);
        return $this->createUnionOrSingleType($types);
    }
    /**
     * @param Type[] $types
     */
    public function createMixedPassedOrUnionType(array $types) : \_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        $types = $this->unwrapUnionedTypes($types);
        $types = $this->uniquateTypes($types);
        return $this->createUnionOrSingleType($types);
    }
    /**
     * @param string[] $allTypes
     * @return ObjectType|UnionType
     */
    public function createObjectTypeOrUnionType(array $allTypes) : \_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        if (\count($allTypes) === 1) {
            return new \_PhpScopere8e811afab72\PHPStan\Type\ObjectType($allTypes[0]);
        }
        if (\count($allTypes) > 1) {
            // keep original order, UnionType internally overrides it → impossible to get first type back, e.g. class over interface
            return \_PhpScopere8e811afab72\Rector\PHPStan\TypeFactoryStaticHelper::createUnionObjectType($allTypes);
        }
        throw new \_PhpScopere8e811afab72\Rector\Core\Exception\ShouldNotHappenException();
    }
    /**
     * @param Type[] $types
     * @return Type[]
     */
    public function uniquateTypes(array $types, bool $keepConstant = \false) : array
    {
        $uniqueTypes = [];
        foreach ($types as $type) {
            if (!$keepConstant) {
                $type = $this->removeValueFromConstantType($type);
            }
            if ($type instanceof \_PhpScopere8e811afab72\Rector\PHPStan\Type\ShortenedObjectType) {
                $type = new \_PhpScopere8e811afab72\Rector\PHPStan\Type\FullyQualifiedObjectType($type->getFullyQualifiedName());
            }
            $typeHash = \md5($type->describe(\_PhpScopere8e811afab72\PHPStan\Type\VerbosityLevel::cache()));
            $uniqueTypes[$typeHash] = $type;
        }
        // re-index
        return \array_values($uniqueTypes);
    }
    /**
     * @param Type[] $types
     * @return Type[]
     */
    private function unwrapUnionedTypes(array $types) : array
    {
        // unwrap union types
        $unwrappedTypes = [];
        foreach ($types as $key => $type) {
            if ($type instanceof \_PhpScopere8e811afab72\PHPStan\Type\UnionType) {
                $unwrappedTypes = \array_merge($unwrappedTypes, $type->getTypes());
                unset($types[$key]);
            }
        }
        $types = \array_merge($types, $unwrappedTypes);
        // re-index
        return \array_values($types);
    }
    /**
     * @param Type[] $types
     */
    private function createUnionOrSingleType(array $types) : \_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        if ($types === []) {
            return new \_PhpScopere8e811afab72\PHPStan\Type\MixedType();
        }
        if (\count($types) === 1) {
            return $types[0];
        }
        return \_PhpScopere8e811afab72\Rector\PHPStan\TypeFactoryStaticHelper::createUnionObjectType($types);
    }
    private function removeValueFromConstantType(\_PhpScopere8e811afab72\PHPStan\Type\Type $type) : \_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        // remove values from constant types
        if ($type instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantFloatType) {
            return new \_PhpScopere8e811afab72\PHPStan\Type\FloatType();
        }
        if ($type instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantStringType) {
            return new \_PhpScopere8e811afab72\PHPStan\Type\StringType();
        }
        if ($type instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantIntegerType) {
            return new \_PhpScopere8e811afab72\PHPStan\Type\IntegerType();
        }
        if ($type instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantBooleanType) {
            return new \_PhpScopere8e811afab72\PHPStan\Type\BooleanType();
        }
        return $type;
    }
}
