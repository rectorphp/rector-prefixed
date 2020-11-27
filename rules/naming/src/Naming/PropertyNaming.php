<?php

declare (strict_types=1);
namespace Rector\Naming\Naming;

use _PhpScoperbd5d0c5f7638\Nette\Utils\Strings;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\RectorNamingInflector;
use Rector\Naming\ValueObject\ExpectedName;
use Rector\NetteKdyby\Naming\VariableNaming;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStan\Type\SelfObjectType;
use Rector\PHPStan\Type\ShortenedObjectType;
use Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper;
/**
 * @deprecated
 * @todo merge with very similar logic in
 * @see VariableNaming
 * @see \Rector\Naming\Tests\Naming\PropertyNamingTest
 */
final class PropertyNaming
{
    /**
     * @var string[]
     */
    private const EXCLUDED_CLASSES = ['#Closure#', '#^Spl#', '#FileInfo#', '#^std#', '#Iterator#', '#SimpleXML#'];
    /**
     * @var string
     */
    private const INTERFACE = 'Interface';
    /**
     * @see https://regex101.com/r/RDhBNR/1
     * @var string
     */
    private const PREFIXED_CLASS_METHODS_REGEX = '#^(is|are|was|were|has|have|had|can)[A-Z].+#';
    /**
     * @var string
     * @see https://regex101.com/r/U78rUF/1
     */
    private const I_PREFIX_REGEX = '#^I[A-Z]#';
    /**
     * @see https://regex101.com/r/hnU5pm/2/
     * @var string
     */
    private const GET_PREFIX_REGEX = '#^get([A-Z].+)#';
    /**
     * @var TypeUnwrapper
     */
    private $typeUnwrapper;
    /**
     * @var RectorNamingInflector
     */
    private $rectorNamingInflector;
    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    public function __construct(\Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper $typeUnwrapper, \Rector\Naming\RectorNamingInflector $rectorNamingInflector, \Rector\Core\PhpParser\Node\BetterNodeFinder $betterNodeFinder, \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver)
    {
        $this->typeUnwrapper = $typeUnwrapper;
        $this->rectorNamingInflector = $rectorNamingInflector;
        $this->betterNodeFinder = $betterNodeFinder;
        $this->nodeNameResolver = $nodeNameResolver;
    }
    public function getExpectedNameFromMethodName(string $methodName) : ?\Rector\Naming\ValueObject\ExpectedName
    {
        $matches = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::match($methodName, self::GET_PREFIX_REGEX);
        if ($matches === null) {
            return null;
        }
        $originalName = \lcfirst($matches[1]);
        return new \Rector\Naming\ValueObject\ExpectedName($originalName, $this->rectorNamingInflector->singularize($originalName));
    }
    public function getExpectedNameFromType(\PHPStan\Type\Type $type) : ?\Rector\Naming\ValueObject\ExpectedName
    {
        if ($type instanceof \PHPStan\Type\UnionType) {
            $type = $this->typeUnwrapper->unwrapNullableType($type);
        }
        if (!$type instanceof \PHPStan\Type\TypeWithClassName) {
            return null;
        }
        if ($type instanceof \Rector\PHPStan\Type\SelfObjectType) {
            return null;
        }
        if ($type instanceof \PHPStan\Type\StaticType) {
            return null;
        }
        $className = $this->getClassName($type);
        foreach (self::EXCLUDED_CLASSES as $excludedClass) {
            if (\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::match($className, $excludedClass)) {
                return null;
            }
        }
        $shortClassName = $this->resolveShortClassName($className);
        $shortClassName = $this->removePrefixesAndSuffixes($shortClassName);
        // if all is upper-cased, it should be lower-cased
        if ($shortClassName === \strtoupper($shortClassName)) {
            $shortClassName = \strtolower($shortClassName);
        }
        // remove "_"
        $shortClassName = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::replace($shortClassName, '#_#', '');
        $shortClassName = $this->normalizeUpperCase($shortClassName);
        // prolong too short generic names with one namespace up
        $originalName = $this->prolongIfTooShort($shortClassName, $className);
        return new \Rector\Naming\ValueObject\ExpectedName($originalName, $this->rectorNamingInflector->singularize($originalName));
    }
    /**
     * @param ObjectType|string $objectType
     */
    public function fqnToVariableName($objectType) : string
    {
        $className = $this->resolveClassName($objectType);
        $shortName = $this->fqnToShortName($className);
        $shortName = $this->removeInterfaceSuffixPrefix($className, $shortName);
        // prolong too short generic names with one namespace up
        return $this->prolongIfTooShort($shortName, $className);
    }
    /**
     * @source https://stackoverflow.com/a/2792045/1348344
     */
    public function underscoreToName(string $underscoreName) : string
    {
        $pascalCaseName = \str_replace('_', '', \ucwords($underscoreName, '_'));
        return \lcfirst($pascalCaseName);
    }
    public function getExpectedNameFromBooleanPropertyType(\PhpParser\Node\Stmt\Property $property) : ?string
    {
        $prefixedClassMethods = $this->getPrefixedClassMethods($property);
        if ($prefixedClassMethods === []) {
            return null;
        }
        $classMethods = $this->filterClassMethodsWithPropertyFetchReturnOnly($prefixedClassMethods, $property);
        if (\count($classMethods) !== 1) {
            return null;
        }
        $classMethod = \reset($classMethods);
        return $this->nodeNameResolver->getName($classMethod);
    }
    private function getClassName(\PHPStan\Type\TypeWithClassName $typeWithClassName) : string
    {
        if ($typeWithClassName instanceof \Rector\PHPStan\Type\ShortenedObjectType) {
            return $typeWithClassName->getFullyQualifiedName();
        }
        return $typeWithClassName->getClassName();
    }
    private function resolveShortClassName(string $className) : string
    {
        if (\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::contains($className, '\\')) {
            return \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::after($className, '\\', -1);
        }
        return $className;
    }
    private function removePrefixesAndSuffixes(string $shortClassName) : string
    {
        // is SomeInterface
        if (\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::endsWith($shortClassName, self::INTERFACE)) {
            $shortClassName = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::substring($shortClassName, 0, -\strlen(self::INTERFACE));
        }
        // is ISomeClass
        if ($this->isPrefixedInterface($shortClassName)) {
            $shortClassName = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::substring($shortClassName, 1);
        }
        // is AbstractClass
        if (\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::startsWith($shortClassName, 'Abstract')) {
            $shortClassName = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::substring($shortClassName, \strlen('Abstract'));
        }
        return $shortClassName;
    }
    private function normalizeUpperCase(string $shortClassName) : string
    {
        // turns $SOMEUppercase => $someUppercase
        for ($i = 0; $i <= \strlen($shortClassName); ++$i) {
            if (\ctype_upper($shortClassName[$i]) && $this->isNumberOrUpper($shortClassName[$i + 1])) {
                $shortClassName[$i] = \strtolower($shortClassName[$i]);
            } else {
                break;
            }
        }
        return $shortClassName;
    }
    private function prolongIfTooShort(string $shortClassName, string $className) : string
    {
        if (\in_array($shortClassName, ['Factory', 'Repository'], \true)) {
            /** @var string $namespaceAbove */
            $namespaceAbove = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::after($className, '\\', -2);
            /** @var string $namespaceAbove */
            $namespaceAbove = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::before($namespaceAbove, '\\');
            return \lcfirst($namespaceAbove) . $shortClassName;
        }
        return \lcfirst($shortClassName);
    }
    /**
     * @param ObjectType|string $objectType
     */
    private function resolveClassName($objectType) : string
    {
        if ($objectType instanceof \PHPStan\Type\ObjectType) {
            return $objectType->getClassName();
        }
        return $objectType;
    }
    private function fqnToShortName(string $fqn) : string
    {
        if (!\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::contains($fqn, '\\')) {
            return $fqn;
        }
        /** @var string $lastNamePart */
        $lastNamePart = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::after($fqn, '\\', -1);
        if (\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::endsWith($lastNamePart, self::INTERFACE)) {
            return \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::substring($lastNamePart, 0, -\strlen(self::INTERFACE));
        }
        return $lastNamePart;
    }
    private function removeInterfaceSuffixPrefix(string $className, string $shortName) : string
    {
        // remove interface prefix/suffix
        if (!\interface_exists($className)) {
            return $shortName;
        }
        // starts with "I\W+"?
        if (\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::match($shortName, self::I_PREFIX_REGEX)) {
            return \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::substring($shortName, 1);
        }
        if (\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::endsWith($shortName, self::INTERFACE)) {
            return \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::substring($shortName, -\strlen(self::INTERFACE));
        }
        return $shortName;
    }
    /**
     * @return ClassMethod[]
     */
    private function getPrefixedClassMethods(\PhpParser\Node\Stmt\Property $property) : array
    {
        $name = $this->nodeNameResolver->getName($property);
        if ($name === null) {
            return [];
        }
        $classLike = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            return [];
        }
        $classMethods = $this->betterNodeFinder->findInstanceOf($classLike, \PhpParser\Node\Stmt\ClassMethod::class);
        return \array_filter($classMethods, function (\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool {
            $classMethodName = $this->nodeNameResolver->getName($classMethod);
            return \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::match($classMethodName, self::PREFIXED_CLASS_METHODS_REGEX) !== null;
        });
    }
    /**
     * @param ClassMethod[] $prefixedClassMethods
     * @return ClassMethod[]
     */
    private function filterClassMethodsWithPropertyFetchReturnOnly(array $prefixedClassMethods, \PhpParser\Node\Stmt\Property $property) : array
    {
        $currentName = $this->nodeNameResolver->getName($property);
        if ($currentName === null) {
            return [];
        }
        return \array_filter($prefixedClassMethods, function (\PhpParser\Node\Stmt\ClassMethod $classMethod) use($currentName) : bool {
            $stmts = $classMethod->stmts;
            if ($stmts === null) {
                return \false;
            }
            if (!\array_key_exists(0, $stmts)) {
                return \false;
            }
            $return = $stmts[0];
            if (!$return instanceof \PhpParser\Node\Stmt\Return_) {
                return \false;
            }
            $node = $return->expr;
            if ($node === null) {
                return \false;
            }
            return $this->nodeNameResolver->isName($node, $currentName);
        });
    }
    private function isPrefixedInterface(string $shortClassName) : bool
    {
        if (\strlen($shortClassName) <= 3) {
            return \false;
        }
        if (!\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::startsWith($shortClassName, 'I')) {
            return \false;
        }
        return \ctype_upper($shortClassName[1]) && \ctype_lower($shortClassName[2]);
    }
    private function isNumberOrUpper(string $char) : bool
    {
        if (\ctype_upper($char)) {
            return \true;
        }
        return \ctype_digit($char);
    }
}