<?php

declare (strict_types=1);
namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
final class FuncCallNameResolver implements \Rector\NodeNameResolver\Contract\NodeNameResolverInterface
{
    /**
     * @var ReflectionProvider
     */
    private $reflectionProvider;
    /**
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider
     */
    public function __construct($reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }
    public function getNode() : string
    {
        return \PhpParser\Node\Expr\FuncCall::class;
    }
    /**
     * If some function is namespaced, it will be used over global one.
     * But only if it really exists.
     *
     * @param FuncCall $node
     */
    public function resolve(\PhpParser\Node $node) : ?string
    {
        if ($node->name instanceof \PhpParser\Node\Expr) {
            return null;
        }
        $functionName = $node->name;
        if (!$functionName instanceof \PhpParser\Node\Name) {
            return (string) $functionName;
        }
        $namespaceName = $functionName->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NAMESPACED_NAME);
        if ($namespaceName instanceof \PhpParser\Node\Name\FullyQualified) {
            $functionFqnName = $namespaceName->toString();
            if ($this->reflectionProvider->hasFunction($namespaceName, null)) {
                return $functionFqnName;
            }
        }
        return (string) $functionName;
    }
}
