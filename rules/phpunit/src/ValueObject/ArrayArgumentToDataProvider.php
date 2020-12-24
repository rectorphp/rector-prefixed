<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\PHPUnit\ValueObject;

final class ArrayArgumentToDataProvider
{
    /**
     * @var string
     */
    private $class;
    /**
     * @var string
     */
    private $oldMethod;
    /**
     * @var string
     */
    private $newMethod;
    /**
     * @var string
     */
    private $variableName;
    public function __construct(string $class, string $oldMethod, string $newMethod, string $variableName)
    {
        $this->class = $class;
        $this->oldMethod = $oldMethod;
        $this->newMethod = $newMethod;
        $this->variableName = $variableName;
    }
    public function getClass() : string
    {
        return $this->class;
    }
    public function getOldMethod() : string
    {
        return $this->oldMethod;
    }
    public function getNewMethod() : string
    {
        return $this->newMethod;
    }
    public function getVariableName() : string
    {
        return $this->variableName;
    }
}
