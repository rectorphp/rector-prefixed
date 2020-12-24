<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Order;

use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassConst;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassLike;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Interface_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Trait_;
use _PhpScoperb75b35f52b74\Rector\NodeNameResolver\NodeNameResolver;
use _PhpScoperb75b35f52b74\Rector\Order\Contract\RankeableInterface;
use _PhpScoperb75b35f52b74\Rector\Order\ValueObject\ClassConstRankeable;
use _PhpScoperb75b35f52b74\Rector\Order\ValueObject\ClassMethodRankeable;
use _PhpScoperb75b35f52b74\Rector\Order\ValueObject\PropertyRankeable;
final class StmtVisibilitySorter
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    public function __construct(\_PhpScoperb75b35f52b74\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
    }
    /**
     * @param Class_|Trait_ $classLike
     * @return string[]
     */
    public function sortProperties(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassLike $classLike) : array
    {
        $propertyRankeables = [];
        foreach ($classLike->stmts as $position => $propertyStmt) {
            if (!$propertyStmt instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Property) {
                continue;
            }
            /** @var string $propertyName */
            $propertyName = $this->nodeNameResolver->getName($propertyStmt);
            $propertyRankeables[] = new \_PhpScoperb75b35f52b74\Rector\Order\ValueObject\PropertyRankeable($propertyName, $this->getVisibilityLevelOrder($propertyStmt), $propertyStmt, $position);
        }
        return $this->sortByRanksAndGetNames($propertyRankeables);
    }
    /**
     * @return string[]
     */
    public function sortMethods(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassLike $classLike) : array
    {
        $classMethodsRankeables = [];
        foreach ($classLike->stmts as $position => $classStmt) {
            if (!$classStmt instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod) {
                continue;
            }
            /** @var string $classMethodName */
            $classMethodName = $this->nodeNameResolver->getName($classStmt);
            $classMethodsRankeables[] = new \_PhpScoperb75b35f52b74\Rector\Order\ValueObject\ClassMethodRankeable($classMethodName, $this->getVisibilityLevelOrder($classStmt), $position, $classStmt);
        }
        return $this->sortByRanksAndGetNames($classMethodsRankeables);
    }
    /**
     * @param Class_|Interface_ $classLike
     * @return string[]
     */
    public function sortConstants(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassLike $classLike) : array
    {
        $classConstsRankeables = [];
        foreach ($classLike->stmts as $position => $constantStmt) {
            if (!$constantStmt instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassConst) {
                continue;
            }
            /** @var string $constantName */
            $constantName = $this->nodeNameResolver->getName($constantStmt);
            $classConstsRankeables[] = new \_PhpScoperb75b35f52b74\Rector\Order\ValueObject\ClassConstRankeable($constantName, $this->getVisibilityLevelOrder($constantStmt), $position);
        }
        return $this->sortByRanksAndGetNames($classConstsRankeables);
    }
    /**
     * @param ClassMethod|Property|ClassConst $stmt
     */
    private function getVisibilityLevelOrder(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt $stmt) : int
    {
        if ($stmt->isPrivate()) {
            return 2;
        }
        if ($stmt->isProtected()) {
            return 1;
        }
        return 0;
    }
    /**
     * @param RankeableInterface[] $rankeables
     * @return string[]
     */
    private function sortByRanksAndGetNames(array $rankeables) : array
    {
        \uasort($rankeables, function (\_PhpScoperb75b35f52b74\Rector\Order\Contract\RankeableInterface $firstRankeable, \_PhpScoperb75b35f52b74\Rector\Order\Contract\RankeableInterface $secondRankeable) : int {
            return $firstRankeable->getRanks() <=> $secondRankeable->getRanks();
        });
        $names = [];
        foreach ($rankeables as $rankeable) {
            $names[] = $rankeable->getName();
        }
        return $names;
    }
}
