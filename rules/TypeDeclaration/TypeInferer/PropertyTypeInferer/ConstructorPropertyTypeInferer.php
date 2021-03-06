<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use RectorPrefix20210317\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeTraverser;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use Rector\Core\NodeManipulator\ClassMethodPropertyFetchManipulator;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\TypeDeclaration\Contract\TypeInferer\PropertyTypeInfererInterface;
use RectorPrefix20210317\Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;
final class ConstructorPropertyTypeInferer implements \Rector\TypeDeclaration\Contract\TypeInferer\PropertyTypeInfererInterface
{
    /**
     * @var ClassMethodPropertyFetchManipulator
     */
    private $classMethodPropertyFetchManipulator;
    /**
     * @var ReflectionProvider
     */
    private $reflectionProvider;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var SimpleCallableNodeTraverser
     */
    private $simpleCallableNodeTraverser;
    /**
     * @var TypeFactory
     */
    private $typeFactory;
    /**
     * @var StaticTypeMapper
     */
    private $staticTypeMapper;
    /**
     * @var NodeTypeResolver
     */
    private $nodeTypeResolver;
    /**
     * @param \Rector\Core\NodeManipulator\ClassMethodPropertyFetchManipulator $classMethodPropertyFetchManipulator
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider
     * @param \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver
     * @param \Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser $simpleCallableNodeTraverser
     * @param \Rector\NodeTypeResolver\PHPStan\Type\TypeFactory $typeFactory
     * @param \Rector\StaticTypeMapper\StaticTypeMapper $staticTypeMapper
     * @param \Rector\NodeTypeResolver\NodeTypeResolver $nodeTypeResolver
     */
    public function __construct($classMethodPropertyFetchManipulator, $reflectionProvider, $nodeNameResolver, $simpleCallableNodeTraverser, $typeFactory, $staticTypeMapper, $nodeTypeResolver)
    {
        $this->classMethodPropertyFetchManipulator = $classMethodPropertyFetchManipulator;
        $this->reflectionProvider = $reflectionProvider;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->simpleCallableNodeTraverser = $simpleCallableNodeTraverser;
        $this->typeFactory = $typeFactory;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->nodeTypeResolver = $nodeTypeResolver;
    }
    /**
     * @param \PhpParser\Node\Stmt\Property $property
     */
    public function inferProperty($property) : \PHPStan\Type\Type
    {
        $classLike = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
            // anonymous class
            return new \PHPStan\Type\MixedType();
        }
        $classMethod = $classLike->getMethod(\Rector\Core\ValueObject\MethodName::CONSTRUCT);
        if (!$classMethod instanceof \PhpParser\Node\Stmt\ClassMethod) {
            return new \PHPStan\Type\MixedType();
        }
        $propertyName = $this->nodeNameResolver->getName($property);
        $param = $this->classMethodPropertyFetchManipulator->resolveParamForPropertyFetch($classMethod, $propertyName);
        if (!$param instanceof \PhpParser\Node\Param) {
            return new \PHPStan\Type\MixedType();
        }
        // A. infer from type declaration of parameter
        if ($param->type !== null) {
            return $this->resolveFromParamType($param, $classMethod, $propertyName);
        }
        return new \PHPStan\Type\MixedType();
    }
    public function getPriority() : int
    {
        return 800;
    }
    /**
     * @param \PhpParser\Node\Param $param
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     * @param string $propertyName
     */
    private function resolveFromParamType($param, $classMethod, $propertyName) : \PHPStan\Type\Type
    {
        $type = $this->resolveParamTypeToPHPStanType($param);
        if ($type instanceof \PHPStan\Type\MixedType) {
            return new \PHPStan\Type\MixedType();
        }
        $types = [];
        // it's an array - annotation → make type more precise, if possible
        if ($type instanceof \PHPStan\Type\ArrayType || $param->variadic) {
            $types[] = $this->getResolveParamStaticTypeAsPHPStanType($classMethod, $propertyName);
        } else {
            $types[] = $type;
        }
        if ($this->isParamNullable($param)) {
            $types[] = new \PHPStan\Type\NullType();
        }
        return $this->typeFactory->createMixedPassedOrUnionType($types);
    }
    /**
     * @param \PhpParser\Node\Param $param
     */
    private function resolveParamTypeToPHPStanType($param) : \PHPStan\Type\Type
    {
        if ($param->type === null) {
            return new \PHPStan\Type\MixedType();
        }
        if ($param->type instanceof \PhpParser\Node\NullableType) {
            $types = [];
            $types[] = new \PHPStan\Type\NullType();
            $types[] = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type->type);
            return $this->typeFactory->createMixedPassedOrUnionType($types);
        }
        // special case for alias
        if ($param->type instanceof \PhpParser\Node\Name\FullyQualified) {
            $type = $this->resolveFullyQualifiedOrAliasedObjectType($param);
            if ($type !== null) {
                return $type;
            }
        }
        return $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     * @param string $propertyName
     */
    private function getResolveParamStaticTypeAsPHPStanType($classMethod, $propertyName) : \PHPStan\Type\Type
    {
        $paramStaticType = new \PHPStan\Type\ArrayType(new \PHPStan\Type\MixedType(), new \PHPStan\Type\MixedType());
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable((array) $classMethod->stmts, function (\PhpParser\Node $node) use($propertyName, &$paramStaticType) : ?int {
            if (!$node instanceof \PhpParser\Node\Expr\Variable) {
                return null;
            }
            if (!$this->nodeNameResolver->isName($node, $propertyName)) {
                return null;
            }
            $paramStaticType = $this->nodeTypeResolver->getStaticType($node);
            return \PhpParser\NodeTraverser::STOP_TRAVERSAL;
        });
        return $paramStaticType;
    }
    /**
     * @param \PhpParser\Node\Param $param
     */
    private function isParamNullable($param) : bool
    {
        if ($param->type instanceof \PhpParser\Node\NullableType) {
            return \true;
        }
        if ($param->default !== null) {
            $defaultValueStaticType = $this->nodeTypeResolver->getStaticType($param->default);
            if ($defaultValueStaticType instanceof \PHPStan\Type\NullType) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @param \PhpParser\Node\Param $param
     */
    private function resolveFullyQualifiedOrAliasedObjectType($param) : ?\PHPStan\Type\Type
    {
        if ($param->type === null) {
            return null;
        }
        $fullyQualifiedName = $this->nodeNameResolver->getName($param->type);
        if (!$fullyQualifiedName) {
            return null;
        }
        $originalName = $param->type->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NAME);
        if (!$originalName instanceof \PhpParser\Node\Name) {
            return null;
        }
        // if the FQN has different ending than the original, it was aliased and we need to return the alias
        if (!\RectorPrefix20210317\Nette\Utils\Strings::endsWith($fullyQualifiedName, '\\' . $originalName->toString())) {
            $className = $originalName->toString();
            if ($this->reflectionProvider->hasClass($className)) {
                return new \Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType($className);
            }
            // @note: $fullyQualifiedName is a guess, needs real life test
            return new \Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType($originalName->toString(), $fullyQualifiedName);
        }
        return null;
    }
}
