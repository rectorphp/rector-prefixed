<?php

declare (strict_types=1);
namespace Rector\Naming\ValueObjectFactory;

use PhpParser\Node\Stmt\Property;
use Rector\Naming\Contract\ExpectedNameResolver\ExpectedNameResolverInterface;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
/**
 * @see \Rector\Naming\Tests\ValueObjectFactory\PropertyRenameFactory\PropertyRenameFactoryTest
 */
final class PropertyRenameFactory
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    public function __construct(\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
    }
    public function create(\PhpParser\Node\Stmt\Property $property, \Rector\Naming\Contract\ExpectedNameResolver\ExpectedNameResolverInterface $expectedNameResolver) : ?\Rector\Naming\ValueObject\PropertyRename
    {
        if (\count($property->props) !== 1) {
            return null;
        }
        $expectedName = $expectedNameResolver->resolveIfNotYet($property);
        if ($expectedName === null) {
            return null;
        }
        $currentName = $this->nodeNameResolver->getName($property);
        $propertyClassLike = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($propertyClassLike === null) {
            return null;
        }
        $propertyClassLikeName = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        if ($propertyClassLikeName === null) {
            return null;
        }
        return new \Rector\Naming\ValueObject\PropertyRename($property, $expectedName, $currentName, $propertyClassLike, $propertyClassLikeName, $property->props[0]);
    }
}
