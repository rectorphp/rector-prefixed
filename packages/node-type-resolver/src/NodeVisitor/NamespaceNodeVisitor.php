<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\NodeTypeResolver\NodeVisitor;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Declare_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Namespace_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Use_;
use _PhpScopere8e811afab72\PhpParser\NodeVisitorAbstract;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\Node\BetterNodeFinder;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
final class NamespaceNodeVisitor extends \_PhpScopere8e811afab72\PhpParser\NodeVisitorAbstract
{
    /**
     * @var Declare_[]
     */
    private $declares = [];
    /**
     * @var Use_[]
     */
    private $useNodes = [];
    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;
    /**
     * @var string|null
     */
    private $namespaceName;
    /**
     * @var Namespace_|null
     */
    private $namespace;
    public function __construct(\_PhpScopere8e811afab72\Rector\Core\PhpParser\Node\BetterNodeFinder $betterNodeFinder)
    {
        $this->betterNodeFinder = $betterNodeFinder;
    }
    /**
     * @param Node[] $nodes
     * @return Node[]|null
     */
    public function beforeTraverse(array $nodes) : ?array
    {
        $this->namespaceName = null;
        $this->namespace = null;
        // init basic use nodes for non-namespaced code
        /** @var Use_[] $uses */
        $uses = $this->betterNodeFinder->findInstanceOf($nodes, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Use_::class);
        $this->useNodes = $uses;
        /** @var Declare_[] $declares */
        $declares = $this->betterNodeFinder->findInstanceOf($nodes, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Declare_::class);
        $this->declares = $declares;
        return null;
    }
    public function enterNode(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Namespace_) {
            $this->namespaceName = $node->name !== null ? $node->name->toString() : null;
            $this->namespace = $node;
            /** @var Use_[] $uses */
            $uses = $this->betterNodeFinder->findInstanceOf($node, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Use_::class);
            $this->useNodes = $uses;
        }
        $node->setAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::NAMESPACE_NAME, $this->namespaceName);
        $node->setAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::NAMESPACE_NODE, $this->namespace);
        $node->setAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::USE_NODES, $this->useNodes);
        $node->setAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::DECLARES, $this->declares);
        return $node;
    }
}
