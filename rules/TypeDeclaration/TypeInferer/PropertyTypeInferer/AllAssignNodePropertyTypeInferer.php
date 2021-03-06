<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\TypeDeclaration\Contract\TypeInferer\PropertyTypeInfererInterface;
use Rector\TypeDeclaration\TypeInferer\AssignToPropertyTypeInferer;
final class AllAssignNodePropertyTypeInferer implements \Rector\TypeDeclaration\Contract\TypeInferer\PropertyTypeInfererInterface
{
    /**
     * @var AssignToPropertyTypeInferer
     */
    private $assignToPropertyTypeInferer;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @param \Rector\TypeDeclaration\TypeInferer\AssignToPropertyTypeInferer $assignToPropertyTypeInferer
     * @param \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver
     */
    public function __construct($assignToPropertyTypeInferer, $nodeNameResolver)
    {
        $this->assignToPropertyTypeInferer = $assignToPropertyTypeInferer;
        $this->nodeNameResolver = $nodeNameResolver;
    }
    /**
     * @param \PhpParser\Node\Stmt\Property $property
     */
    public function inferProperty($property) : \PHPStan\Type\Type
    {
        $classLike = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\ClassLike) {
            // anonymous class
            return new \PHPStan\Type\MixedType();
        }
        $propertyName = $this->nodeNameResolver->getName($property);
        return $this->assignToPropertyTypeInferer->inferPropertyInClassLike($propertyName, $classLike);
    }
    public function getPriority() : int
    {
        return 1500;
    }
}
