<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\NodeTraverser;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\VoidType;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\TypeDeclaration\Contract\TypeInferer\ReturnTypeInfererInterface;
use Rector\TypeDeclaration\TypeInferer\SilentVoidResolver;
use Rector\TypeDeclaration\TypeInferer\SplArrayFixedTypeNarrower;
use RectorPrefix20210317\Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;
final class ReturnedNodesReturnTypeInferer implements \Rector\TypeDeclaration\Contract\TypeInferer\ReturnTypeInfererInterface
{
    /**
     * @var Type[]
     */
    private $types = [];
    /**
     * @var SilentVoidResolver
     */
    private $silentVoidResolver;
    /**
     * @var NodeTypeResolver
     */
    private $nodeTypeResolver;
    /**
     * @var SimpleCallableNodeTraverser
     */
    private $simpleCallableNodeTraverser;
    /**
     * @var TypeFactory
     */
    private $typeFactory;
    /**
     * @var SplArrayFixedTypeNarrower
     */
    private $splArrayFixedTypeNarrower;
    /**
     * @param \Rector\TypeDeclaration\TypeInferer\SilentVoidResolver $silentVoidResolver
     * @param \Rector\NodeTypeResolver\NodeTypeResolver $nodeTypeResolver
     * @param \Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser $simpleCallableNodeTraverser
     * @param \Rector\NodeTypeResolver\PHPStan\Type\TypeFactory $typeFactory
     * @param \Rector\TypeDeclaration\TypeInferer\SplArrayFixedTypeNarrower $splArrayFixedTypeNarrower
     */
    public function __construct($silentVoidResolver, $nodeTypeResolver, $simpleCallableNodeTraverser, $typeFactory, $splArrayFixedTypeNarrower)
    {
        $this->silentVoidResolver = $silentVoidResolver;
        $this->nodeTypeResolver = $nodeTypeResolver;
        $this->simpleCallableNodeTraverser = $simpleCallableNodeTraverser;
        $this->typeFactory = $typeFactory;
        $this->splArrayFixedTypeNarrower = $splArrayFixedTypeNarrower;
    }
    /**
     * @param ClassMethod|Closure|Function_ $functionLike
     */
    public function inferFunctionLike($functionLike) : \PHPStan\Type\Type
    {
        /** @var Class_|Trait_|Interface_|null $classLike */
        $classLike = $functionLike->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            return new \PHPStan\Type\MixedType();
        }
        if ($functionLike instanceof \PhpParser\Node\Stmt\ClassMethod && $classLike instanceof \PhpParser\Node\Stmt\Interface_) {
            return new \PHPStan\Type\MixedType();
        }
        $this->types = [];
        $localReturnNodes = $this->collectReturns($functionLike);
        if ($localReturnNodes === []) {
            return $this->resolveNoLocalReturnNodes($classLike, $functionLike);
        }
        $hasSilentVoid = $this->silentVoidResolver->hasSilentVoid($functionLike);
        foreach ($localReturnNodes as $localReturnNode) {
            $returnedExprType = $this->nodeTypeResolver->getStaticType($localReturnNode);
            $returnedExprType = $this->splArrayFixedTypeNarrower->narrow($returnedExprType);
            $this->types[] = $returnedExprType;
        }
        if ($hasSilentVoid) {
            $this->types[] = new \PHPStan\Type\VoidType();
        }
        return $this->typeFactory->createMixedPassedOrUnionType($this->types);
    }
    public function getPriority() : int
    {
        return 1000;
    }
    /**
     * @return Return_[]
     * @param \PhpParser\Node\FunctionLike $functionLike
     */
    private function collectReturns($functionLike) : array
    {
        $returns = [];
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable((array) $functionLike->getStmts(), function (\PhpParser\Node $node) use(&$returns) : ?int {
            if ($node instanceof \PhpParser\Node\Stmt\Switch_) {
                $this->processSwitch($node);
            }
            // skip Return_ nodes in nested functions or switch statements
            if ($node instanceof \PhpParser\Node\FunctionLike) {
                return \PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }
            if (!$node instanceof \PhpParser\Node\Stmt\Return_) {
                return null;
            }
            $returns[] = $node;
            return null;
        });
        return $returns;
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassLike $classLike
     * @param \PhpParser\Node\FunctionLike $functionLike
     */
    private function resolveNoLocalReturnNodes($classLike, $functionLike) : \PHPStan\Type\Type
    {
        // void type
        if (!$this->isAbstractMethod($classLike, $functionLike)) {
            return new \PHPStan\Type\VoidType();
        }
        return new \PHPStan\Type\MixedType();
    }
    /**
     * @param \PhpParser\Node\Stmt\Switch_ $switch
     */
    private function processSwitch($switch) : void
    {
        foreach ($switch->cases as $case) {
            if ($case->cond === null) {
                return;
            }
        }
        $this->types[] = new \PHPStan\Type\VoidType();
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassLike $classLike
     * @param \PhpParser\Node\FunctionLike $functionLike
     */
    private function isAbstractMethod($classLike, $functionLike) : bool
    {
        if ($functionLike instanceof \PhpParser\Node\Stmt\ClassMethod && $functionLike->isAbstract()) {
            return \true;
        }
        if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
            return \false;
        }
        return $classLike->isAbstract();
    }
}
