<?php

declare (strict_types=1);
namespace Rector\Naming\PropertyRenamer;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\VarLikeIdentifier;
use Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser;
use Rector\Naming\Contract\Guard\ConflictingGuardInterface;
use Rector\Naming\Contract\RenameGuard\RenameGuardInterface;
use Rector\Naming\Contract\RenamerInterface;
use Rector\Naming\Contract\RenameValueObjectInterface;
use Rector\Naming\Guard\DateTimeAtNamingConventionGuard;
use Rector\Naming\Guard\HasMagicGetSetGuard;
use Rector\Naming\Guard\NotPrivatePropertyGuard;
use Rector\Naming\Guard\RamseyUuidInterfaceGuard;
use Rector\Naming\RenameGuard\PropertyRenameGuard;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\NodeNameResolver\NodeNameResolver;
abstract class AbstractPropertyRenamer implements \Rector\Naming\Contract\RenamerInterface
{
    /**
     * @var RenameGuardInterface
     */
    protected $propertyRenameGuard;
    /**
     * @var ConflictingGuardInterface
     */
    protected $conflictingPropertyNameGuard;
    /**
     * @var CallableNodeTraverser
     */
    private $callableNodeTraverser;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var NotPrivatePropertyGuard
     */
    private $notPrivatePropertyGuard;
    /**
     * @var RamseyUuidInterfaceGuard
     */
    private $ramseyUuidInterfaceGuard;
    /**
     * @var DateTimeAtNamingConventionGuard
     */
    private $dateTimeAtNamingConventionGuard;
    /**
     * @var HasMagicGetSetGuard
     */
    private $hasMagicGetSetGuard;
    /**
     * @required
     */
    public function autowireAbstractPropertyRenamer(\Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser $callableNodeTraverser, \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \Rector\Naming\Guard\NotPrivatePropertyGuard $notPrivatePropertyGuard, \Rector\Naming\Guard\RamseyUuidInterfaceGuard $ramseyUuidInterfaceGuard, \Rector\Naming\Guard\DateTimeAtNamingConventionGuard $dateTimeAtNamingConventionGuard, \Rector\Naming\RenameGuard\PropertyRenameGuard $propertyRenameGuard, \Rector\Naming\Guard\HasMagicGetSetGuard $hasMagicGetSetGuard) : void
    {
        $this->callableNodeTraverser = $callableNodeTraverser;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->notPrivatePropertyGuard = $notPrivatePropertyGuard;
        $this->ramseyUuidInterfaceGuard = $ramseyUuidInterfaceGuard;
        $this->dateTimeAtNamingConventionGuard = $dateTimeAtNamingConventionGuard;
        $this->propertyRenameGuard = $propertyRenameGuard;
        $this->hasMagicGetSetGuard = $hasMagicGetSetGuard;
    }
    /**
     * @param PropertyRename $renameValueObject
     * @return Property|null
     */
    public function rename(\Rector\Naming\Contract\RenameValueObjectInterface $renameValueObject) : ?\PhpParser\Node
    {
        if (!$this->areNamesDifferent($renameValueObject)) {
            return null;
        }
        if ($this->propertyRenameGuard->shouldSkip($renameValueObject, [$this->notPrivatePropertyGuard, $this->conflictingPropertyNameGuard, $this->ramseyUuidInterfaceGuard, $this->dateTimeAtNamingConventionGuard, $this->hasMagicGetSetGuard])) {
            return null;
        }
        $onlyPropertyProperty = $renameValueObject->getPropertyProperty();
        $onlyPropertyProperty->name = new \PhpParser\Node\VarLikeIdentifier($renameValueObject->getExpectedName());
        $this->renamePropertyFetchesInClass($renameValueObject);
        return $renameValueObject->getProperty();
    }
    private function areNamesDifferent(\Rector\Naming\ValueObject\PropertyRename $propertyRename) : bool
    {
        return $propertyRename->getCurrentName() !== $propertyRename->getExpectedName();
    }
    private function renamePropertyFetchesInClass(\Rector\Naming\ValueObject\PropertyRename $propertyRename) : void
    {
        // 1. replace property fetch rename in whole class
        $this->callableNodeTraverser->traverseNodesWithCallable($propertyRename->getClassLike(), function (\PhpParser\Node $node) use($propertyRename) : ?Node {
            if ($this->nodeNameResolver->isLocalPropertyFetchNamed($node, $propertyRename->getCurrentName()) && $node instanceof \PhpParser\Node\Expr\PropertyFetch) {
                $node->name = new \PhpParser\Node\Identifier($propertyRename->getExpectedName());
                return $node;
            }
            if ($this->nodeNameResolver->isLocalStaticPropertyFetchNamed($node, $propertyRename->getCurrentName())) {
                if (!$node instanceof \PhpParser\Node\Expr\StaticPropertyFetch) {
                    return null;
                }
                $node->name = new \PhpParser\Node\VarLikeIdentifier($propertyRename->getExpectedName());
                return $node;
            }
            return null;
        });
    }
}
