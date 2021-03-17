<?php

declare (strict_types=1);
namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
final class NameNameResolver implements \Rector\NodeNameResolver\Contract\NodeNameResolverInterface
{
    /**
     * @var FuncCallNameResolver
     */
    private $funcCallNameResolver;
    /**
     * @param \Rector\NodeNameResolver\NodeNameResolver\FuncCallNameResolver $funcCallNameResolver
     */
    public function __construct($funcCallNameResolver)
    {
        $this->funcCallNameResolver = $funcCallNameResolver;
    }
    public function getNode() : string
    {
        return \PhpParser\Node\Name::class;
    }
    /**
     * @param Name $node
     */
    public function resolve(\PhpParser\Node $node) : ?string
    {
        // possible function parent
        $parent = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parent instanceof \PhpParser\Node\Expr\FuncCall) {
            return $this->funcCallNameResolver->resolve($parent);
        }
        $resolvedName = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::RESOLVED_NAME);
        if ($resolvedName instanceof \PhpParser\Node\Name\FullyQualified) {
            return $resolvedName->toString();
        }
        return $node->toString();
    }
}
