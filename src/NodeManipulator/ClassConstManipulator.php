<?php

declare (strict_types=1);
namespace Rector\Core\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Trait_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\NodeCollector\NodeCollector\NodeRepository;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
final class ClassConstManipulator
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;
    /**
     * @var BetterStandardPrinter
     */
    private $betterStandardPrinter;
    /**
     * @var ClassManipulator
     */
    private $classManipulator;
    /**
     * @var NodeRepository
     */
    private $nodeRepository;
    public function __construct(\Rector\Core\PhpParser\Node\BetterNodeFinder $betterNodeFinder, \Rector\Core\PhpParser\Printer\BetterStandardPrinter $betterStandardPrinter, \Rector\Core\NodeManipulator\ClassManipulator $classManipulator, \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \Rector\NodeCollector\NodeCollector\NodeRepository $nodeRepository)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->betterNodeFinder = $betterNodeFinder;
        $this->betterStandardPrinter = $betterStandardPrinter;
        $this->classManipulator = $classManipulator;
        $this->nodeRepository = $nodeRepository;
    }
    /**
     * @return ClassConstFetch[]
     */
    public function getAllClassConstFetch(\PhpParser\Node\Stmt\ClassConst $classConst) : array
    {
        $classLike = $classConst->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
            return [];
        }
        $searchInNodes = [$classLike];
        $usedTraitNames = $this->classManipulator->getUsedTraits($classLike);
        foreach ($usedTraitNames as $name) {
            $name = $this->nodeRepository->findTrait((string) $name);
            if (!$name instanceof \PhpParser\Node\Stmt\Trait_) {
                continue;
            }
            $searchInNodes[] = $name;
        }
        return $this->betterNodeFinder->find($searchInNodes, function (\PhpParser\Node $node) use($classConst) : bool {
            // itself
            if ($this->betterStandardPrinter->areNodesEqual($node, $classConst)) {
                return \false;
            }
            // property + static fetch
            if (!$node instanceof \PhpParser\Node\Expr\ClassConstFetch) {
                return \false;
            }
            return $this->isNameMatch($node, $classConst);
        });
    }
    /**
     * @see https://github.com/myclabs/php-enum#declaration
     */
    public function isEnum(\PhpParser\Node\Stmt\ClassConst $classConst) : bool
    {
        $classLike = $classConst->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
            return \false;
        }
        if ($classLike->extends === null) {
            return \false;
        }
        return $this->nodeNameResolver->isName($classLike->extends, '*Enum');
    }
    private function isNameMatch(\PhpParser\Node\Expr\ClassConstFetch $classConstFetch, \PhpParser\Node\Stmt\ClassConst $classConst) : bool
    {
        $selfConstantName = 'self::' . $this->nodeNameResolver->getName($classConst);
        $staticConstantName = 'static::' . $this->nodeNameResolver->getName($classConst);
        return $this->nodeNameResolver->isNames($classConstFetch, [$selfConstantName, $staticConstantName]);
    }
}
