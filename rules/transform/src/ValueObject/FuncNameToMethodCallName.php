<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Transform\ValueObject;

final class FuncNameToMethodCallName
{
    /**
     * @var string
     */
    private $oldFuncName;
    /**
     * @var string
     */
    private $newClassName;
    /**
     * @var string
     */
    private $newMethodName;
    public function __construct(string $oldFuncName, string $newClassName, string $newMethodName)
    {
        $this->oldFuncName = $oldFuncName;
        $this->newClassName = $newClassName;
        $this->newMethodName = $newMethodName;
    }
    public function getOldFuncName() : string
    {
        return $this->oldFuncName;
    }
    public function getNewClassName() : string
    {
        return $this->newClassName;
    }
    public function getNewMethodName() : string
    {
        return $this->newMethodName;
    }
}
