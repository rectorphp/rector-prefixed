<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Exception;

use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\ReflectionClass;
use UnexpectedValueException;
use function sprintf;
class NotAClassReflection extends \UnexpectedValueException
{
    public static function fromReflectionClass(\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\ReflectionClass $class) : self
    {
        $type = 'interface';
        if ($class->isTrait()) {
            $type = 'trait';
        }
        return new self(\sprintf('Provided node "%s" is not class, but "%s"', $class->getName(), $type));
    }
}
