<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\NetteCodeQuality\Contract;

use _PhpScoperb75b35f52b74\Rector\NetteCodeQuality\NodeResolver\MethodNamesByInputNamesResolver;
interface MethodNamesByInputNamesResolverAwareInterface
{
    public function setResolver(\_PhpScoperb75b35f52b74\Rector\NetteCodeQuality\NodeResolver\MethodNamesByInputNamesResolver $methodNamesByInputNamesResolver) : void;
}
