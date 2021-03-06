<?php

declare (strict_types=1);
namespace Rector\NetteCodeQuality\FormControlTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\TypeWithClassName;
use Rector\NetteCodeQuality\Contract\FormControlTypeResolverInterface;
use Rector\NetteCodeQuality\Naming\NetteControlNaming;
use Rector\NetteCodeQuality\NodeAnalyzer\ControlDimFetchAnalyzer;
use Rector\NodeCollector\NodeCollector\NodeRepository;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
final class ArrayDimFetchControlTypeResolver implements \Rector\NetteCodeQuality\Contract\FormControlTypeResolverInterface
{
    /**
     * @var ControlDimFetchAnalyzer
     */
    private $controlDimFetchAnalyzer;
    /**
     * @var NodeTypeResolver
     */
    private $nodeTypeResolver;
    /**
     * @var NetteControlNaming
     */
    private $netteControlNaming;
    /**
     * @var ReturnTypeInferer
     */
    private $returnTypeInferer;
    /**
     * @var NodeRepository
     */
    private $nodeRepository;
    /**
     * @param \Rector\NetteCodeQuality\NodeAnalyzer\ControlDimFetchAnalyzer $controlDimFetchAnalyzer
     * @param \Rector\NetteCodeQuality\Naming\NetteControlNaming $netteControlNaming
     * @param \Rector\NodeTypeResolver\NodeTypeResolver $nodeTypeResolver
     * @param \Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer $returnTypeInferer
     * @param \Rector\NodeCollector\NodeCollector\NodeRepository $nodeRepository
     */
    public function __construct($controlDimFetchAnalyzer, $netteControlNaming, $nodeTypeResolver, $returnTypeInferer, $nodeRepository)
    {
        $this->controlDimFetchAnalyzer = $controlDimFetchAnalyzer;
        $this->nodeTypeResolver = $nodeTypeResolver;
        $this->netteControlNaming = $netteControlNaming;
        $this->returnTypeInferer = $returnTypeInferer;
        $this->nodeRepository = $nodeRepository;
    }
    /**
     * @return array<string, string>
     * @param \PhpParser\Node $node
     */
    public function resolve($node) : array
    {
        if (!$node instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            return [];
        }
        $controlShortName = $this->controlDimFetchAnalyzer->matchName($node);
        if ($controlShortName === null) {
            return [];
        }
        $createComponentClassMethod = $this->matchCreateComponentClassMethod($node, $controlShortName);
        if (!$createComponentClassMethod instanceof \PhpParser\Node\Stmt\ClassMethod) {
            return [];
        }
        $createComponentClassMethodReturnType = $this->returnTypeInferer->inferFunctionLike($createComponentClassMethod);
        if (!$createComponentClassMethodReturnType instanceof \PHPStan\Type\TypeWithClassName) {
            return [];
        }
        return [$controlShortName => $createComponentClassMethodReturnType->getClassName()];
    }
    /**
     * @param \PhpParser\Node\Expr\ArrayDimFetch $arrayDimFetch
     * @param string $controlShortName
     */
    private function matchCreateComponentClassMethod($arrayDimFetch, $controlShortName) : ?\PhpParser\Node\Stmt\ClassMethod
    {
        $callerType = $this->nodeTypeResolver->getStaticType($arrayDimFetch->var);
        if (!$callerType instanceof \PHPStan\Type\TypeWithClassName) {
            return null;
        }
        $createComponentClassMethodName = $this->netteControlNaming->createCreateComponentClassMethodName($controlShortName);
        return $this->nodeRepository->findClassMethod($callerType->getClassName(), $createComponentClassMethodName);
    }
}
