<?php

declare (strict_types=1);
namespace Rector\Restoration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Restoration\Type\ConstantReturnToParamTypeConverter;
use Rector\Restoration\ValueObject\InferParamFromClassMethodReturn;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use RectorPrefix20210317\Webmozart\Assert\Assert;
/**
 * @see \Rector\Tests\Restoration\Rector\ClassMethod\InferParamFromClassMethodReturnRector\InferParamFromClassMethodReturnRectorTest
 */
final class InferParamFromClassMethodReturnRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const INFER_PARAMS_FROM_CLASS_METHOD_RETURNS = 'infer_param_from_class_method_returns';
    /**
     * @var InferParamFromClassMethodReturn[]
     */
    private $inferParamFromClassMethodReturn = [];
    /**
     * @var ReturnTypeInferer
     */
    private $returnTypeInferer;
    /**
     * @var ConstantReturnToParamTypeConverter
     */
    private $constantReturnToParamTypeConverter;
    /**
     * @var PhpDocTypeChanger
     */
    private $phpDocTypeChanger;
    /**
     * @param \Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer $returnTypeInferer
     * @param \Rector\Restoration\Type\ConstantReturnToParamTypeConverter $constantReturnToParamTypeConverter
     * @param \Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger $phpDocTypeChanger
     */
    public function __construct($returnTypeInferer, $constantReturnToParamTypeConverter, $phpDocTypeChanger)
    {
        $this->returnTypeInferer = $returnTypeInferer;
        $this->constantReturnToParamTypeConverter = $constantReturnToParamTypeConverter;
        $this->phpDocTypeChanger = $phpDocTypeChanger;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change @param doc based on another method return type', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    public function process(Node $node)
    {
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    /**
     * @param String_ $node
     */
    public function process(Node $node)
    {
    }
}
CODE_SAMPLE
, [self::INFER_PARAMS_FROM_CLASS_METHOD_RETURNS => [new \Rector\Restoration\ValueObject\InferParamFromClassMethodReturn('SomeClass', 'process', 'getNodeTypes')]])]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        // must be exactly 1 param
        if (\count($node->params) !== 1) {
            return null;
        }
        $firstParam = $node->params[0];
        $paramName = $this->getName($firstParam);
        foreach ($this->inferParamFromClassMethodReturn as $singleInferParamFromClassMethodReturn) {
            $returnClassMethod = $this->matchReturnClassMethod($node, $singleInferParamFromClassMethodReturn);
            if (!$returnClassMethod instanceof \PhpParser\Node\Stmt\ClassMethod) {
                continue;
            }
            $returnType = $this->returnTypeInferer->inferFunctionLike($returnClassMethod);
            $currentPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
            $paramType = $this->constantReturnToParamTypeConverter->convert($returnType);
            if ($paramType instanceof \PHPStan\Type\MixedType) {
                continue;
            }
            if ($this->isParamDocTypeEqualToPhpType($firstParam, $paramType)) {
                return null;
            }
            $this->phpDocTypeChanger->changeParamType($currentPhpDocInfo, $paramType, $firstParam, $paramName);
            return $node;
        }
        return null;
    }
    /**
     * @param array<string, mixed[]> $configuration
     */
    public function configure($configuration) : void
    {
        $inferParamsFromClassMethodReturns = $configuration[self::INFER_PARAMS_FROM_CLASS_METHOD_RETURNS] ?? [];
        \RectorPrefix20210317\Webmozart\Assert\Assert::allIsInstanceOf($inferParamsFromClassMethodReturns, \Rector\Restoration\ValueObject\InferParamFromClassMethodReturn::class);
        $this->inferParamFromClassMethodReturn = $inferParamsFromClassMethodReturns;
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     * @param \Rector\Restoration\ValueObject\InferParamFromClassMethodReturn $inferParamFromClassMethodReturn
     */
    private function matchReturnClassMethod($classMethod, $inferParamFromClassMethodReturn) : ?\PhpParser\Node\Stmt\ClassMethod
    {
        if (!$this->nodeNameResolver->isInClassNamed($classMethod, $inferParamFromClassMethodReturn->getObjectType())) {
            return null;
        }
        if (!$this->isName($classMethod->name, $inferParamFromClassMethodReturn->getParamMethod())) {
            return null;
        }
        $classLike = $classMethod->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
            return null;
        }
        return $classLike->getMethod($inferParamFromClassMethodReturn->getReturnMethod());
    }
    /**
     * @param \PhpParser\Node\Param $param
     * @param \PHPStan\Type\Type $paramType
     */
    private function isParamDocTypeEqualToPhpType($param, $paramType) : bool
    {
        $currentParamType = $this->getObjectType($param);
        if ($currentParamType instanceof \PHPStan\Type\UnionType) {
            $currentParamType = $currentParamType->getTypes()[0];
        }
        return $currentParamType->equals($paramType);
    }
}
