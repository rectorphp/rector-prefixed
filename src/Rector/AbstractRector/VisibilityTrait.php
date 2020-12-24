<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassConst;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Property;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator\VisibilityManipulator;
/**
 * This could be part of @see AbstractRector, but decopuling to trait
 * makes clear what code has 1 purpose.
 */
trait VisibilityTrait
{
    /**
     * @var VisibilityManipulator
     */
    private $visibilityManipulator;
    /**
     * @required
     */
    public function autowireVisibilityTrait(\_PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator\VisibilityManipulator $visibilityManipulator) : void
    {
        $this->visibilityManipulator = $visibilityManipulator;
    }
    /**
     * @param ClassMethod|Property|ClassConst $node
     */
    public function changeNodeVisibility(\_PhpScopere8e811afab72\PhpParser\Node $node, string $visibility) : void
    {
        $this->visibilityManipulator->changeNodeVisibility($node, $visibility);
    }
    /**
     * @param ClassMethod|Class_ $node
     */
    public function makeFinal(\_PhpScopere8e811afab72\PhpParser\Node $node) : void
    {
        $this->visibilityManipulator->makeFinal($node);
    }
    /**
     * @param ClassMethod|Property|ClassConst $node
     */
    public function removeVisibility(\_PhpScopere8e811afab72\PhpParser\Node $node) : void
    {
        $this->visibilityManipulator->removeOriginalVisibilityFromFlags($node);
    }
    /**
     * @param ClassMethod|Class_ $node
     */
    public function makeAbstract(\_PhpScopere8e811afab72\PhpParser\Node $node) : void
    {
        $this->visibilityManipulator->makeAbstract($node);
    }
    /**
     * @param ClassMethod|Property|ClassConst $node
     */
    public function makePublic(\_PhpScopere8e811afab72\PhpParser\Node $node) : void
    {
        $this->visibilityManipulator->makePublic($node);
    }
    /**
     * @param ClassMethod|Property|ClassConst $node
     */
    public function makeProtected(\_PhpScopere8e811afab72\PhpParser\Node $node) : void
    {
        $this->visibilityManipulator->makeProtected($node);
    }
    /**
     * @param ClassMethod|Property|ClassConst $node
     */
    public function makePrivate(\_PhpScopere8e811afab72\PhpParser\Node $node) : void
    {
        $this->visibilityManipulator->makePrivate($node);
    }
    /**
     * @param ClassMethod|Property|ClassConst $node
     */
    public function makeStatic(\_PhpScopere8e811afab72\PhpParser\Node $node) : void
    {
        $this->visibilityManipulator->makeStatic($node);
    }
    /**
     * @param ClassMethod|Property $node
     */
    public function makeNonStatic(\_PhpScopere8e811afab72\PhpParser\Node $node) : void
    {
        $this->visibilityManipulator->makeNonStatic($node);
    }
    /**
     * @param ClassMethod|Class_ $node
     */
    public function makeNonFinal(\_PhpScopere8e811afab72\PhpParser\Node $node) : void
    {
        $this->visibilityManipulator->makeNonFinal($node);
    }
}
