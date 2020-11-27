<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflector;

use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\Identifier;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\IdentifierType;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflection\Reflection;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflection\ReflectionClass;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Type\SourceLocator;
use function array_key_exists;
use function assert;
use function strtolower;
class ClassReflector implements \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflector\Reflector
{
    /** @var SourceLocator */
    private $sourceLocator;
    /** @var (ReflectionClass|null)[] */
    private $cachedReflections = [];
    public function __construct(\_PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Type\SourceLocator $sourceLocator)
    {
        $this->sourceLocator = $sourceLocator;
    }
    /**
     * Create a ReflectionClass for the specified $className.
     *
     * @return ReflectionClass
     *
     * @throws IdentifierNotFound
     */
    public function reflect(string $className) : \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflection\Reflection
    {
        $lowerClassName = \strtolower($className);
        if (\array_key_exists($lowerClassName, $this->cachedReflections)) {
            $classInfo = $this->cachedReflections[$lowerClassName];
        } else {
            $identifier = new \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\Identifier($className, new \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\IdentifierType(\_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\IdentifierType::IDENTIFIER_CLASS));
            $classInfo = $this->sourceLocator->locateIdentifier($this, $identifier);
            \assert($classInfo instanceof \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflection\ReflectionClass || $classInfo === null);
            $this->cachedReflections[$lowerClassName] = $classInfo;
        }
        if ($classInfo === null) {
            if (!isset($identifier)) {
                $identifier = new \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\Identifier($className, new \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\IdentifierType(\_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\IdentifierType::IDENTIFIER_CLASS));
            }
            throw \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflector\Exception\IdentifierNotFound::fromIdentifier($identifier);
        }
        return $classInfo;
    }
    /**
     * Get all the classes available in the scope specified by the SourceLocator.
     *
     * @return ReflectionClass[]
     */
    public function getAllClasses() : array
    {
        /** @var ReflectionClass[] $allClasses */
        $allClasses = $this->sourceLocator->locateIdentifiersByType($this, new \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\IdentifierType(\_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\IdentifierType::IDENTIFIER_CLASS));
        return $allClasses;
    }
}