<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Closure;
use _PhpScoperb75b35f52b74\PhpParser\Node\FunctionLike;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassLike;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Function_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Interface_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Return_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Switch_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Trait_;
use _PhpScoperb75b35f52b74\PhpParser\NodeTraverser;
use _PhpScoperb75b35f52b74\PHPStan\Type\MixedType;
use _PhpScoperb75b35f52b74\PHPStan\Type\Type;
use _PhpScoperb75b35f52b74\PHPStan\Type\VoidType;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoperb75b35f52b74\Rector\TypeDeclaration\Contract\TypeInferer\ReturnTypeInfererInterface;
use _PhpScoperb75b35f52b74\Rector\TypeDeclaration\TypeInferer\AbstractTypeInferer;
use _PhpScoperb75b35f52b74\Rector\TypeDeclaration\TypeInferer\SilentVoidResolver;
final class ReturnedNodesReturnTypeInferer extends \_PhpScoperb75b35f52b74\Rector\TypeDeclaration\TypeInferer\AbstractTypeInferer implements \_PhpScoperb75b35f52b74\Rector\TypeDeclaration\Contract\TypeInferer\ReturnTypeInfererInterface
{
    /**
     * @var Type[]
     */
    private $types = [];
    /**
     * @var SilentVoidResolver
     */
    private $silentVoidResolver;
    public function __construct(\_PhpScoperb75b35f52b74\Rector\TypeDeclaration\TypeInferer\SilentVoidResolver $silentVoidResolver)
    {
        $this->silentVoidResolver = $silentVoidResolver;
    }
    /**
     * @param ClassMethod|Closure|Function_ $functionLike
     */
    public function inferFunctionLike(\_PhpScoperb75b35f52b74\PhpParser\Node\FunctionLike $functionLike) : \_PhpScoperb75b35f52b74\PHPStan\Type\Type
    {
        /** @var Class_|Trait_|Interface_|null $classLike */
        $classLike = $functionLike->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Type\MixedType();
        }
        if ($functionLike instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod && $classLike instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Interface_) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Type\MixedType();
        }
        $this->types = [];
        $localReturnNodes = $this->collectReturns($functionLike);
        if ($localReturnNodes === []) {
            return $this->resolveNoLocalReturnNodes($classLike, $functionLike);
        }
        $hasSilentVoid = $this->silentVoidResolver->hasSilentVoid($functionLike);
        foreach ($localReturnNodes as $localReturnNode) {
            if ($localReturnNode->expr === null) {
                $this->types[] = new \_PhpScoperb75b35f52b74\PHPStan\Type\VoidType();
                continue;
            }
            $exprType = $this->nodeTypeResolver->getStaticType($localReturnNode->expr);
            $this->types[] = $exprType;
        }
        if ($hasSilentVoid) {
            $this->types[] = new \_PhpScoperb75b35f52b74\PHPStan\Type\VoidType();
        }
        return $this->typeFactory->createMixedPassedOrUnionType($this->types);
    }
    public function getPriority() : int
    {
        return 1000;
    }
    /**
     * @return Return_[]
     */
    private function collectReturns(\_PhpScoperb75b35f52b74\PhpParser\Node\FunctionLike $functionLike) : array
    {
        $returns = [];
        $this->callableNodeTraverser->traverseNodesWithCallable((array) $functionLike->getStmts(), function (\_PhpScoperb75b35f52b74\PhpParser\Node $node) use(&$returns) : ?int {
            if ($node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Switch_) {
                $this->processSwitch($node);
            }
            // skip Return_ nodes in nested functions or switch statements
            if ($node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\FunctionLike) {
                return \_PhpScoperb75b35f52b74\PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }
            if (!$node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Return_) {
                return null;
            }
            $returns[] = $node;
            return null;
        });
        return $returns;
    }
    private function resolveNoLocalReturnNodes(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassLike $classLike, \_PhpScoperb75b35f52b74\PhpParser\Node\FunctionLike $functionLike) : \_PhpScoperb75b35f52b74\PHPStan\Type\Type
    {
        // void type
        if (!$this->isAbstractMethod($classLike, $functionLike)) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Type\VoidType();
        }
        return new \_PhpScoperb75b35f52b74\PHPStan\Type\MixedType();
    }
    private function processSwitch(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Switch_ $switch) : void
    {
        foreach ($switch->cases as $case) {
            if ($case->cond === null) {
                return;
            }
        }
        $this->types[] = new \_PhpScoperb75b35f52b74\PHPStan\Type\VoidType();
    }
    private function isAbstractMethod(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassLike $classLike, \_PhpScoperb75b35f52b74\PhpParser\Node\FunctionLike $functionLike) : bool
    {
        if ($functionLike instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod && $functionLike->isAbstract()) {
            return \true;
        }
        if (!$classLike instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_) {
            return \false;
        }
        return $classLike->isAbstract();
    }
}
