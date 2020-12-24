<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\DeadDocBlock;

use _PhpScopere8e811afab72\PhpParser\Node\Param;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod;
use _PhpScopere8e811afab72\PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use _PhpScopere8e811afab72\Rector\NodeNameResolver\NodeNameResolver;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\PHPStan\TypeComparator;
final class DeadParamTagValueNodeAnalyzer
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var TypeComparator
     */
    private $typeComparator;
    public function __construct(\_PhpScopere8e811afab72\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \_PhpScopere8e811afab72\Rector\NodeTypeResolver\PHPStan\TypeComparator $typeComparator)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->typeComparator = $typeComparator;
    }
    public function isDead(\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode $paramTagValueNode, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        $param = $this->matchParamByName($paramTagValueNode->parameterName, $classMethod);
        if ($param === null) {
            return \false;
        }
        if ($param->type === null) {
            return \false;
        }
        if (!$this->typeComparator->arePhpParserAndPhpStanPhpDocTypesEqual($param->type, $paramTagValueNode->type, $classMethod)) {
            return \false;
        }
        return $paramTagValueNode->description === '';
    }
    private function matchParamByName(string $desiredParamName, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod $classMethod) : ?\_PhpScopere8e811afab72\PhpParser\Node\Param
    {
        foreach ((array) $classMethod->params as $param) {
            $paramName = $this->nodeNameResolver->getName($param);
            if ('$' . $paramName !== $desiredParamName) {
                continue;
            }
            return $param;
        }
        return null;
    }
}
