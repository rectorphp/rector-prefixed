<?php

declare (strict_types=1);
namespace Rector\DependencyInjection\NodeAnalyzer;

use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Nette\NetteInjectTagNode;
use Rector\Core\ValueObject\MethodName;
use Rector\FamilyTree\NodeAnalyzer\ClassChildAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
final class NetteInjectPropertyAnalyzer
{
    /**
     * @var ClassChildAnalyzer
     */
    private $classChildAnalyzer;
    public function __construct(\Rector\FamilyTree\NodeAnalyzer\ClassChildAnalyzer $classChildAnalyzer)
    {
        $this->classChildAnalyzer = $classChildAnalyzer;
    }
    public function detect(\PhpParser\Node\Stmt\Property $property, \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo $phpDocInfo) : bool
    {
        if (!$phpDocInfo->hasByType(\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Nette\NetteInjectTagNode::class)) {
            return \false;
        }
        /** @var Scope $scope */
        $scope = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE);
        $classReflection = $scope->getClassReflection();
        if (!$classReflection instanceof \PHPStan\Reflection\ClassReflection) {
            return \false;
        }
        if ($classReflection->isAbstract()) {
            return \false;
        }
        if ($classReflection->isAnonymous()) {
            return \false;
        }
        if ($this->classChildAnalyzer->hasChildClassMethod($classReflection, \Rector\Core\ValueObject\MethodName::CONSTRUCT)) {
            return \false;
        }
        if ($this->classChildAnalyzer->hasParentClassMethod($classReflection, \Rector\Core\ValueObject\MethodName::CONSTRUCT)) {
            return \false;
        }
        // it needs @var tag as well, to get the type
        if ($phpDocInfo->getVarTagValueNode() !== null) {
            return \true;
        }
        return $property->type !== null;
    }
}
