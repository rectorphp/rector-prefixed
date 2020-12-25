<?php

declare (strict_types=1);
namespace PHPStan\Reflection\Php;

use PHPStan\TrinaryLogic;
class FakeBuiltinMethodReflection implements \PHPStan\Reflection\Php\BuiltinMethodReflection
{
    /** @var string */
    private $methodName;
    /** @var \ReflectionClass */
    private $declaringClass;
    public function __construct(string $methodName, \ReflectionClass $declaringClass)
    {
        $this->methodName = $methodName;
        $this->declaringClass = $declaringClass;
    }
    public function getName() : string
    {
        return $this->methodName;
    }
    public function getReflection() : ?\ReflectionMethod
    {
        return null;
    }
    /**
     * @return string|false
     */
    public function getFileName()
    {
        return \false;
    }
    public function getDeclaringClass() : \ReflectionClass
    {
        return $this->declaringClass;
    }
    /**
     * @return int|false
     */
    public function getStartLine()
    {
        return \false;
    }
    /**
     * @return int|false
     */
    public function getEndLine()
    {
        return \false;
    }
    public function getDocComment() : ?string
    {
        return null;
    }
    public function isStatic() : bool
    {
        return \false;
    }
    public function isPrivate() : bool
    {
        return \false;
    }
    public function isPublic() : bool
    {
        return \true;
    }
    public function getPrototype() : \PHPStan\Reflection\Php\BuiltinMethodReflection
    {
        throw new \ReflectionException();
    }
    public function isDeprecated() : \PHPStan\TrinaryLogic
    {
        return \PHPStan\TrinaryLogic::createNo();
    }
    public function isVariadic() : bool
    {
        return \false;
    }
    public function isFinal() : bool
    {
        return \false;
    }
    public function isInternal() : bool
    {
        return \false;
    }
    public function isAbstract() : bool
    {
        return \false;
    }
    public function getReturnType() : ?\ReflectionType
    {
        return null;
    }
    /**
     * @return \ReflectionParameter[]
     */
    public function getParameters() : array
    {
        return [];
    }
}
