<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\PHPStanStaticTypeMapper\TypeMapper;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Name;
use _PhpScopere8e811afab72\PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use _PhpScopere8e811afab72\PHPStan\PhpDocParser\Ast\Type\TypeNode;
use _PhpScopere8e811afab72\PHPStan\Type\ArrayType;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantArrayType;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantIntegerType;
use _PhpScopere8e811afab72\PHPStan\Type\IntegerType;
use _PhpScopere8e811afab72\PHPStan\Type\MixedType;
use _PhpScopere8e811afab72\PHPStan\Type\NeverType;
use _PhpScopere8e811afab72\PHPStan\Type\Type;
use _PhpScopere8e811afab72\PHPStan\Type\UnionType;
use _PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareArrayTypeNode;
use _PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareGenericTypeNode;
use _PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareIdentifierTypeNode;
use _PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareUnionTypeNode;
use _PhpScopere8e811afab72\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface;
use _PhpScopere8e811afab72\Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use _PhpScopere8e811afab72\Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use _PhpScopere8e811afab72\Rector\TypeDeclaration\TypeNormalizer;
/**
 * @see \Rector\PHPStanStaticTypeMapper\Tests\TypeMapper\ArrayTypeMapperTest
 */
final class ArrayTypeMapper implements \_PhpScopere8e811afab72\Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface
{
    /**
     * @var string
     */
    public const HAS_GENERIC_TYPE_PARENT = 'has_generic_type_parent';
    /**
     * @var PHPStanStaticTypeMapper
     */
    private $phpStanStaticTypeMapper;
    /**
     * @var TypeNormalizer
     */
    private $typeNormalizer;
    /**
     * @required
     */
    public function autowireArrayTypeMapper(\_PhpScopere8e811afab72\Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper $phpStanStaticTypeMapper, \_PhpScopere8e811afab72\Rector\TypeDeclaration\TypeNormalizer $typeNormalizer) : void
    {
        $this->phpStanStaticTypeMapper = $phpStanStaticTypeMapper;
        $this->typeNormalizer = $typeNormalizer;
    }
    public function getNodeClass() : string
    {
        return \_PhpScopere8e811afab72\PHPStan\Type\ArrayType::class;
    }
    /**
     * @param ArrayType $type
     */
    public function mapToPHPStanPhpDocTypeNode(\_PhpScopere8e811afab72\PHPStan\Type\Type $type) : \_PhpScopere8e811afab72\PHPStan\PhpDocParser\Ast\Type\TypeNode
    {
        $itemType = $type->getItemType();
        if ($itemType instanceof \_PhpScopere8e811afab72\PHPStan\Type\UnionType && !$type instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantArrayType) {
            return $this->createUnionType($itemType);
        }
        if ($itemType instanceof \_PhpScopere8e811afab72\PHPStan\Type\ArrayType) {
            $isGenericArrayCandidate = $this->isGenericArrayCandidate($itemType);
            if ($isGenericArrayCandidate) {
                return $this->createGenericArrayType($type, \true);
            }
        }
        $isGenericArrayCandidate = $this->isGenericArrayCandidate($type);
        if ($isGenericArrayCandidate) {
            return $this->createGenericArrayType($type, \true);
        }
        $itemTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($itemType);
        return new \_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareArrayTypeNode($itemTypeNode);
    }
    /**
     * @param ArrayType $type
     */
    public function mapToPhpParserNode(\_PhpScopere8e811afab72\PHPStan\Type\Type $type, ?string $kind = null) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        return new \_PhpScopere8e811afab72\PhpParser\Node\Name('array');
    }
    /**
     * @param ArrayType $type
     */
    public function mapToDocString(\_PhpScopere8e811afab72\PHPStan\Type\Type $type, ?\_PhpScopere8e811afab72\PHPStan\Type\Type $parentType = null) : string
    {
        $itemType = $type->getItemType();
        $normalizedType = $this->typeNormalizer->normalizeArrayOfUnionToUnionArray($type);
        if ($normalizedType instanceof \_PhpScopere8e811afab72\PHPStan\Type\UnionType) {
            return $this->mapArrayUnionTypeToDocString($type, $normalizedType);
        }
        return $this->phpStanStaticTypeMapper->mapToDocString($itemType, $parentType) . '[]';
    }
    private function createUnionType(\_PhpScopere8e811afab72\PHPStan\Type\UnionType $unionType) : \_PhpScopere8e811afab72\PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode
    {
        $unionedArrayType = [];
        foreach ($unionType->getTypes() as $unionedType) {
            $typeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($unionedType);
            $unionedArrayType[(string) $typeNode] = $typeNode;
        }
        if (\count($unionedArrayType) > 1) {
            return new \_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareArrayTypeNode(new \_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareUnionTypeNode($unionedArrayType));
        }
        /** @var TypeNode $arrayType */
        $arrayType = \array_shift($unionedArrayType);
        return new \_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareArrayTypeNode($arrayType);
    }
    private function isGenericArrayCandidate(\_PhpScopere8e811afab72\PHPStan\Type\ArrayType $arrayType) : bool
    {
        if ($arrayType->getKeyType() instanceof \_PhpScopere8e811afab72\PHPStan\Type\MixedType) {
            return \false;
        }
        // skip simple arrays, like "string[]", from converting to obvious "array<int, string>"
        if ($this->isIntegerKeyAndNonNestedArray($arrayType)) {
            return \false;
        }
        if ($arrayType->getKeyType() instanceof \_PhpScopere8e811afab72\PHPStan\Type\NeverType) {
            return \false;
        }
        // make sure the integer key type is not natural/implicit array int keys
        $keysArrayType = $arrayType->getKeysArray();
        if (!$keysArrayType instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantArrayType) {
            return \true;
        }
        foreach ($keysArrayType->getValueTypes() as $key => $keyType) {
            if (!$keyType instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantIntegerType) {
                return \true;
            }
            if ($key !== $keyType->getValue()) {
                return \true;
            }
        }
        return \false;
    }
    private function createGenericArrayType(\_PhpScopere8e811afab72\PHPStan\Type\ArrayType $arrayType, bool $withKey = \false) : \_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareGenericTypeNode
    {
        $itemTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($arrayType->getItemType());
        $attributeAwareIdentifierTypeNode = new \_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareIdentifierTypeNode('array');
        if ($withKey) {
            $keyTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($arrayType->getKeyType());
            $genericTypes = [$keyTypeNode, $itemTypeNode];
        } else {
            $genericTypes = [$itemTypeNode];
        }
        // @see https://github.com/phpstan/phpdoc-parser/blob/98a088b17966bdf6ee25c8a4b634df313d8aa531/tests/PHPStan/Parser/PhpDocParserTest.php#L2692-L2696
        foreach ($genericTypes as $genericType) {
            /** @var AttributeAwareNodeInterface $genericType */
            $genericType->setAttribute(self::HAS_GENERIC_TYPE_PARENT, $withKey);
        }
        $attributeAwareIdentifierTypeNode->setAttribute(self::HAS_GENERIC_TYPE_PARENT, $withKey);
        return new \_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareGenericTypeNode($attributeAwareIdentifierTypeNode, $genericTypes);
    }
    private function mapArrayUnionTypeToDocString(\_PhpScopere8e811afab72\PHPStan\Type\ArrayType $arrayType, \_PhpScopere8e811afab72\PHPStan\Type\UnionType $unionType) : string
    {
        $unionedTypesAsString = [];
        foreach ($unionType->getTypes() as $unionedArrayItemType) {
            $unionedTypesAsString[] = $this->phpStanStaticTypeMapper->mapToDocString($unionedArrayItemType, $arrayType);
        }
        $unionedTypesAsString = \array_values($unionedTypesAsString);
        $unionedTypesAsString = \array_unique($unionedTypesAsString);
        return \implode('|', $unionedTypesAsString);
    }
    private function isIntegerKeyAndNonNestedArray(\_PhpScopere8e811afab72\PHPStan\Type\ArrayType $arrayType) : bool
    {
        if (!$arrayType->getKeyType() instanceof \_PhpScopere8e811afab72\PHPStan\Type\IntegerType) {
            return \false;
        }
        return !$arrayType->getItemType() instanceof \_PhpScopere8e811afab72\PHPStan\Type\ArrayType;
    }
}
