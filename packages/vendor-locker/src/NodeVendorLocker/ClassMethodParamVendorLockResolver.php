<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\VendorLocker\NodeVendorLocker;

use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Interface_;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey;
final class ClassMethodParamVendorLockResolver extends \_PhpScoperb75b35f52b74\Rector\VendorLocker\NodeVendorLocker\AbstractNodeVendorLockResolver
{
    public function isVendorLocked(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod $classMethod, int $paramPosition) : bool
    {
        /** @var Class_|null $classNode */
        $classNode = $classMethod->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classNode === null) {
            return \false;
        }
        if (!$this->hasParentClassChildrenClassesOrImplementsInterface($classNode)) {
            return \false;
        }
        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);
        /** @var string|null $parentClassName */
        $parentClassName = $classMethod->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_CLASS_NAME);
        if ($parentClassName !== null) {
            $vendorLock = $this->isParentClassVendorLocking($paramPosition, $parentClassName, $methodName);
            if ($vendorLock !== null) {
                return $vendorLock;
            }
        }
        $classNode = $classMethod->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classNode instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_ && !$classNode instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Interface_) {
            return \false;
        }
        return $this->isMethodVendorLockedByInterface($classNode, $methodName);
    }
    private function isParentClassVendorLocking(int $paramPosition, string $parentClassName, string $methodName) : ?bool
    {
        $parentClass = $this->parsedNodeCollector->findClass($parentClassName);
        if ($parentClass !== null) {
            $parentClassMethod = $parentClass->getMethod($methodName);
            // parent class method in local scope → it's ok
            if ($parentClassMethod !== null) {
                // parent method has no type → we cannot change it here
                if (!isset($parentClassMethod->params[$paramPosition])) {
                    return \false;
                }
                return $parentClassMethod->params[$paramPosition]->type === null;
            }
        }
        if (\method_exists($parentClassName, $methodName)) {
            // parent class method in external scope → it's not ok
            // if not, look for it's parent parent
            return \true;
        }
        return null;
    }
}
