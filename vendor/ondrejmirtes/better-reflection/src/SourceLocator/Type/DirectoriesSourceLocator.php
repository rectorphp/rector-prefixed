<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Type;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\Identifier;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\IdentifierType;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflection\Reflection;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflector\Reflector;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Ast\Locator;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Exception\InvalidDirectory;
use _PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Exception\InvalidFileInfo;
use function array_map;
use function array_values;
use function is_dir;
use function is_string;
/**
 * This source locator recursively loads all php files in an entire directory or multiple directories.
 */
class DirectoriesSourceLocator implements \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Type\SourceLocator
{
    /** @var AggregateSourceLocator */
    private $aggregateSourceLocator;
    /**
     * @param string[] $directories directories to scan
     *
     * @throws InvalidDirectory
     * @throws InvalidFileInfo
     */
    public function __construct(array $directories, \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Ast\Locator $astLocator)
    {
        $this->aggregateSourceLocator = new \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator(\array_values(\array_map(static function ($directory) use($astLocator) : FileIteratorSourceLocator {
            if (!\is_string($directory)) {
                throw \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Exception\InvalidDirectory::fromNonStringValue($directory);
            }
            if (!\is_dir($directory)) {
                throw \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Exception\InvalidDirectory::fromNonDirectory($directory);
            }
            return new \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Type\FileIteratorSourceLocator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)), $astLocator);
        }, $directories)));
    }
    public function locateIdentifier(\_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\Identifier $identifier) : ?\_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflection\Reflection
    {
        return $this->aggregateSourceLocator->locateIdentifier($reflector, $identifier);
    }
    /**
     * {@inheritDoc}
     */
    public function locateIdentifiersByType(\_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\Identifier\IdentifierType $identifierType) : array
    {
        return $this->aggregateSourceLocator->locateIdentifiersByType($reflector, $identifierType);
    }
}
