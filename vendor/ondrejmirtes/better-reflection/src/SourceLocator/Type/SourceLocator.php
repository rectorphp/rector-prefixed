<?php

declare (strict_types=1);
namespace _PhpScoper88fe6e0ad041\Roave\BetterReflection\SourceLocator\Type;

use _PhpScoper88fe6e0ad041\Roave\BetterReflection\Identifier\Identifier;
use _PhpScoper88fe6e0ad041\Roave\BetterReflection\Identifier\IdentifierType;
use _PhpScoper88fe6e0ad041\Roave\BetterReflection\Reflection\Reflection;
use _PhpScoper88fe6e0ad041\Roave\BetterReflection\Reflector\Reflector;
interface SourceLocator
{
    /**
     * Locate some source code.
     *
     * This method should return a LocatedSource value object or `null` if the
     * SourceLocator is unable to locate the source.
     *
     * NOTE: A SourceLocator should *NOT* throw an exception if it is unable to
     * locate the identifier, it should simply return null. If an exception is
     * thrown, it will break the Generic Reflector.
     */
    public function locateIdentifier(\_PhpScoper88fe6e0ad041\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoper88fe6e0ad041\Roave\BetterReflection\Identifier\Identifier $identifier) : ?\_PhpScoper88fe6e0ad041\Roave\BetterReflection\Reflection\Reflection;
    /**
     * Find all identifiers of a type
     *
     * @return Reflection[]
     */
    public function locateIdentifiersByType(\_PhpScoper88fe6e0ad041\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoper88fe6e0ad041\Roave\BetterReflection\Identifier\IdentifierType $identifierType) : array;
}
