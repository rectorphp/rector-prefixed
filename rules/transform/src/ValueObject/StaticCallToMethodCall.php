<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Transform\ValueObject;

use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Identifier;
use _PhpScoperb75b35f52b74\PhpParser\Node\Name;
final class StaticCallToMethodCall
{
    /**
     * @var string
     */
    private $staticClass;
    /**
     * @var string
     */
    private $staticMethod;
    /**
     * @var string
     */
    private $classType;
    /**
     * @var string
     */
    private $methodName;
    public function __construct(string $staticClass, string $staticMethod, string $classType, string $methodName)
    {
        $this->staticClass = $staticClass;
        $this->staticMethod = $staticMethod;
        $this->classType = $classType;
        $this->methodName = $methodName;
    }
    public function getClassType() : string
    {
        return $this->classType;
    }
    public function getMethodName() : string
    {
        return $this->methodName;
    }
    public function isStaticCallMatch(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall $staticCall) : bool
    {
        if (!$staticCall->class instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Name) {
            return \false;
        }
        $staticCallClassName = $staticCall->class->toString();
        if ($staticCallClassName !== $this->staticClass) {
            return \false;
        }
        if (!$staticCall->name instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Identifier) {
            return \false;
        }
        // all methods
        if ($this->staticMethod === '*') {
            return \true;
        }
        $staticCallMethodName = $staticCall->name->toString();
        return $staticCallMethodName === $this->staticMethod;
    }
}
