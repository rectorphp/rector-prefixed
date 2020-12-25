<?php

declare (strict_types=1);
namespace Rector\CodingStyle\NodeAnalyzer;

use PhpParser\Node\Stmt\Throw_;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;
final class ThrowAnalyzer
{
    /**
     * @var NodeTypeResolver
     */
    private $nodeTypeResolver;
    public function __construct(\Rector\NodeTypeResolver\NodeTypeResolver $nodeTypeResolver)
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
    }
    /**
     * @return string[]
     */
    public function resolveThrownTypes(\PhpParser\Node\Stmt\Throw_ $throw) : array
    {
        $thrownType = $this->nodeTypeResolver->getStaticType($throw->expr);
        if ($thrownType instanceof \PHPStan\Type\MixedType) {
            return [];
        }
        if ($thrownType instanceof \PHPStan\Type\UnionType) {
            $types = [];
            foreach ($thrownType->getTypes() as $unionedType) {
                $types[] = $this->resolveClassFromType($unionedType);
            }
            return $types;
        }
        $class = $this->resolveClassFromType($thrownType);
        return [$class];
    }
    private function resolveClassFromType(\PHPStan\Type\Type $thrownType) : string
    {
        if ($thrownType instanceof \Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType) {
            return $thrownType->getFullyQualifiedName();
        }
        if ($thrownType instanceof \PHPStan\Type\TypeWithClassName) {
            return $thrownType->getClassName();
        }
        throw new \Rector\Core\Exception\ShouldNotHappenException();
    }
}
