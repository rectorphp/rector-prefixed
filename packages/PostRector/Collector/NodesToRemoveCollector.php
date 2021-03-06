<?php

declare (strict_types=1);
namespace Rector\PostRector\Collector;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\ChangesReporting\Collector\AffectedFilesCollector;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeRemoval\BreakingRemovalGuard;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Contract\Collector\NodeCollectorInterface;
use RectorPrefix20210317\Symplify\SmartFileSystem\SmartFileInfo;
final class NodesToRemoveCollector implements \Rector\PostRector\Contract\Collector\NodeCollectorInterface
{
    /**
     * @var AffectedFilesCollector
     */
    private $affectedFilesCollector;
    /**
     * @var BreakingRemovalGuard
     */
    private $breakingRemovalGuard;
    /**
     * @var Stmt[]|Node[]
     */
    private $nodesToRemove = [];
    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;
    /**
     * @var NodeComparator
     */
    private $nodeComparator;
    /**
     * @param \Rector\ChangesReporting\Collector\AffectedFilesCollector $affectedFilesCollector
     * @param \Rector\NodeRemoval\BreakingRemovalGuard $breakingRemovalGuard
     * @param \Rector\Core\PhpParser\Node\BetterNodeFinder $betterNodeFinder
     * @param \Rector\Core\PhpParser\Comparing\NodeComparator $nodeComparator
     */
    public function __construct($affectedFilesCollector, $breakingRemovalGuard, $betterNodeFinder, $nodeComparator)
    {
        $this->affectedFilesCollector = $affectedFilesCollector;
        $this->breakingRemovalGuard = $breakingRemovalGuard;
        $this->betterNodeFinder = $betterNodeFinder;
        $this->nodeComparator = $nodeComparator;
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function addNodeToRemove($node) : void
    {
        /** Node|null $parentNode */
        $parentNode = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parentNode !== null && $this->isUsedInArg($node, $parentNode)) {
            return;
        }
        // chain call: "->method()->another()"
        $this->ensureIsNotPartOfChainMethodCall($node);
        if (!$node instanceof \PhpParser\Node\Stmt\Expression && $parentNode instanceof \PhpParser\Node\Stmt\Expression) {
            // only expressions can be removed
            $node = $parentNode;
        } else {
            $this->breakingRemovalGuard->ensureNodeCanBeRemove($node);
        }
        /** @var SmartFileInfo|null $fileInfo */
        $fileInfo = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::FILE_INFO);
        if ($fileInfo !== null) {
            $this->affectedFilesCollector->addFile($fileInfo);
        }
        /** @var Stmt $node */
        $this->nodesToRemove[] = $node;
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function isNodeRemoved($node) : bool
    {
        return \in_array($node, $this->nodesToRemove, \true);
    }
    public function isActive() : bool
    {
        return $this->getCount() > 0;
    }
    public function getCount() : int
    {
        return \count($this->nodesToRemove);
    }
    /**
     * @return Node[]
     */
    public function getNodesToRemove() : array
    {
        return $this->nodesToRemove;
    }
    /**
     * @param int $key
     */
    public function unset($key) : void
    {
        unset($this->nodesToRemove[$key]);
    }
    /**
     * @param \PhpParser\Node $node
     * @param \PhpParser\Node $parentNode
     */
    private function isUsedInArg($node, $parentNode) : bool
    {
        if (!$node instanceof \PhpParser\Node\Param) {
            return \false;
        }
        if (!$parentNode instanceof \PhpParser\Node\Stmt\ClassMethod) {
            return \false;
        }
        $paramVariable = $node->var;
        if ($paramVariable instanceof \PhpParser\Node\Expr\Variable) {
            return (bool) $this->betterNodeFinder->findFirst((array) $parentNode->stmts, function (\PhpParser\Node $variable) use($paramVariable) : bool {
                if (!$this->nodeComparator->areNodesEqual($variable, $paramVariable)) {
                    return \false;
                }
                $hasArgParent = (bool) $this->betterNodeFinder->findParentType($variable, \PhpParser\Node\Arg::class);
                if (!$hasArgParent) {
                    return \false;
                }
                return !(bool) $this->betterNodeFinder->findParentType($variable, \PhpParser\Node\Expr\StaticCall::class);
            });
        }
        return \false;
    }
    /**
     * @param \PhpParser\Node $node
     */
    private function ensureIsNotPartOfChainMethodCall($node) : void
    {
        if (!$node instanceof \PhpParser\Node\Expr\MethodCall) {
            return;
        }
        if (!$node->var instanceof \PhpParser\Node\Expr\MethodCall) {
            return;
        }
        throw new \Rector\Core\Exception\ShouldNotHappenException('Chain method calls cannot be removed this way. It would remove the whole tree of calls. Remove them manually by creating new parent node with no following method.');
    }
}
