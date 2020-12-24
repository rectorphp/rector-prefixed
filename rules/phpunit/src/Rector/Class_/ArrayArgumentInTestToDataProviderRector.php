<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\Class_;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Arg;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Variable;
use _PhpScoperb75b35f52b74\PhpParser\Node\Identifier;
use _PhpScoperb75b35f52b74\PhpParser\Node\Param;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\Type\TypeNode;
use _PhpScoperb75b35f52b74\PHPStan\Type\Type;
use _PhpScoperb75b35f52b74\PHPStan\Type\UnionType;
use _PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareParamTagValueNode;
use _PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocTagNode;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use _PhpScoperb75b35f52b74\Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use _PhpScoperb75b35f52b74\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractPHPUnitRector;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\NodeFactory\DataProviderClassMethodFactory;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\ArrayArgumentToDataProvider;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\DataProviderClassMethodRecipe;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\ParamAndArg;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use _PhpScoperb75b35f52b74\Webmozart\Assert\Assert;
/**
 * @see \Rector\PHPUnit\Tests\Rector\Class_\ArrayArgumentInTestToDataProviderRector\ArrayArgumentInTestToDataProviderRectorTest
 *
 * @see why → https://blog.martinhujer.cz/how-to-use-data-providers-in-phpunit/
 */
final class ArrayArgumentInTestToDataProviderRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractPHPUnitRector implements \_PhpScoperb75b35f52b74\Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const ARRAY_ARGUMENTS_TO_DATA_PROVIDERS = 'array_arguments_to_data_providers';
    /**
     * @var ArrayArgumentToDataProvider[]
     */
    private $arrayArgumentsToDataProviders = [];
    /**
     * @var DataProviderClassMethodRecipe[]
     */
    private $dataProviderClassMethodRecipes = [];
    /**
     * @var DataProviderClassMethodFactory
     */
    private $dataProviderClassMethodFactory;
    /**
     * @var TypeFactory
     */
    private $typeFactory;
    public function __construct(\_PhpScoperb75b35f52b74\Rector\PHPUnit\NodeFactory\DataProviderClassMethodFactory $dataProviderClassMethodFactory, \_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\PHPStan\Type\TypeFactory $typeFactory)
    {
        $this->dataProviderClassMethodFactory = $dataProviderClassMethodFactory;
        $this->typeFactory = $typeFactory;
    }
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Move array argument from tests into data provider [configurable]', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

class SomeServiceTest extends TestCase
{
    public function test()
    {
        $this->doTestMultiple([1, 2, 3]);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

class SomeServiceTest extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(int $number)
    {
        $this->doTestSingle($number);
    }

    public function provideData(): \Iterator
    {
        yield [1];
        yield [2];
        yield [3];
    }
}
CODE_SAMPLE
, [self::ARRAY_ARGUMENTS_TO_DATA_PROVIDERS => [new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\ArrayArgumentToDataProvider('_PhpScoperb75b35f52b74\\PHPUnit\\Framework\\TestCase', 'doTestMultiple', 'doTestSingle', 'number')]])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if (!$this->isInTestClass($node)) {
            return null;
        }
        $this->dataProviderClassMethodRecipes = [];
        $this->traverseNodesWithCallable($node->stmts, function (\_PhpScoperb75b35f52b74\PhpParser\Node $node) {
            if (!$node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall) {
                return null;
            }
            foreach ($this->arrayArgumentsToDataProviders as $arrayArgumentsToDataProvider) {
                $this->refactorMethodCallWithConfiguration($node, $arrayArgumentsToDataProvider);
            }
            return null;
        });
        if ($this->dataProviderClassMethodRecipes === []) {
            return null;
        }
        $dataProviderClassMethods = $this->createDataProviderClassMethodsFromRecipes();
        $node->stmts = \array_merge($node->stmts, $dataProviderClassMethods);
        return $node;
    }
    public function configure(array $arrayArgumentsToDataProviders) : void
    {
        $arrayArgumentsToDataProviders = $arrayArgumentsToDataProviders[self::ARRAY_ARGUMENTS_TO_DATA_PROVIDERS] ?? [];
        \_PhpScoperb75b35f52b74\Webmozart\Assert\Assert::allIsInstanceOf($arrayArgumentsToDataProviders, \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\ArrayArgumentToDataProvider::class);
        $this->arrayArgumentsToDataProviders = $arrayArgumentsToDataProviders;
    }
    private function refactorMethodCallWithConfiguration(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall $methodCall, \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\ArrayArgumentToDataProvider $arrayArgumentToDataProvider) : void
    {
        if (!$this->isMethodCallMatch($methodCall, $arrayArgumentToDataProvider)) {
            return;
        }
        if (\count((array) $methodCall->args) !== 1) {
            throw new \_PhpScoperb75b35f52b74\Rector\Core\Exception\ShouldNotHappenException();
        }
        // resolve value types
        $firstArgumentValue = $methodCall->args[0]->value;
        if (!$firstArgumentValue instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_) {
            // nothing we can do
            return;
        }
        // rename method to new one handling non-array input
        $methodCall->name = new \_PhpScoperb75b35f52b74\PhpParser\Node\Identifier($arrayArgumentToDataProvider->getNewMethod());
        $dataProviderMethodName = $this->createDataProviderMethodName($methodCall);
        $this->dataProviderClassMethodRecipes[] = new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\DataProviderClassMethodRecipe($dataProviderMethodName, $methodCall->args);
        $methodCall->args = [];
        $paramAndArgs = $this->collectParamAndArgsFromArray($firstArgumentValue, $arrayArgumentToDataProvider->getVariableName());
        foreach ($paramAndArgs as $paramAndArg) {
            $methodCall->args[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Arg($paramAndArg->getVariable());
        }
        /** @var ClassMethod $classMethod */
        $classMethod = $methodCall->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::METHOD_NODE);
        $this->refactorTestClassMethodParams($classMethod, $paramAndArgs);
        // add data provider annotation
        $dataProviderTagNode = $this->createDataProviderTagNode($dataProviderMethodName);
        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $classMethod->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        $phpDocInfo->addPhpDocTagNode($dataProviderTagNode);
    }
    /**
     * @return ClassMethod[]
     */
    private function createDataProviderClassMethodsFromRecipes() : array
    {
        $dataProviderClassMethods = [];
        foreach ($this->dataProviderClassMethodRecipes as $dataProviderClassMethodRecipe) {
            $dataProviderClassMethods[] = $this->dataProviderClassMethodFactory->createFromRecipe($dataProviderClassMethodRecipe);
        }
        return $dataProviderClassMethods;
    }
    private function isMethodCallMatch(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall $methodCall, \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\ArrayArgumentToDataProvider $arrayArgumentToDataProvider) : bool
    {
        if (!$this->isObjectType($methodCall->var, $arrayArgumentToDataProvider->getClass())) {
            return \false;
        }
        return $this->isName($methodCall->name, $arrayArgumentToDataProvider->getOldMethod());
    }
    private function createDataProviderMethodName(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall $methodCall) : string
    {
        /** @var string $methodName */
        $methodName = $methodCall->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::METHOD_NAME);
        return 'provideDataFor' . \ucfirst($methodName);
    }
    /**
     * @return ParamAndArg[]
     */
    private function collectParamAndArgsFromArray(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_ $array, string $variableName) : array
    {
        $isNestedArray = $this->isNestedArray($array);
        if ($isNestedArray) {
            return $this->collectParamAndArgsFromNestedArray($array, $variableName);
        }
        $itemsStaticType = $this->resolveItemStaticType($array, $isNestedArray);
        return $this->collectParamAndArgsFromNonNestedArray($array, $variableName, $itemsStaticType);
    }
    /**
     * @param ParamAndArg[] $paramAndArgs
     */
    private function refactorTestClassMethodParams(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod $classMethod, array $paramAndArgs) : void
    {
        $classMethod->params = $this->createParams($paramAndArgs);
        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $classMethod->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        foreach ($paramAndArgs as $paramAndArg) {
            $staticType = $paramAndArg->getType();
            if (!$staticType instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\UnionType) {
                continue;
            }
            /** @var string $paramName */
            $paramName = $this->getName($paramAndArg->getVariable());
            /** @var TypeNode $staticTypeNode */
            $staticTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($staticType);
            $paramTagValueNode = $this->createParamTagNode($paramName, $staticTypeNode);
            $phpDocInfo->addTagValueNode($paramTagValueNode);
        }
    }
    private function createDataProviderTagNode(string $dataProviderMethodName) : \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode
    {
        return new \_PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocTagNode('@dataProvider', new \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode($dataProviderMethodName . '()'));
    }
    private function isNestedArray(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_ $array) : bool
    {
        foreach ($array->items as $arrayItem) {
            if (!$arrayItem instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem) {
                continue;
            }
            if ($arrayItem->value instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @return ParamAndArg[]
     */
    private function collectParamAndArgsFromNestedArray(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_ $array, string $variableName) : array
    {
        $paramAndArgs = [];
        $i = 1;
        foreach ($array->items as $arrayItem) {
            if (!$arrayItem instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem) {
                continue;
            }
            $nestedArray = $arrayItem->value;
            if (!$nestedArray instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_) {
                continue;
            }
            foreach ($nestedArray->items as $nestedArrayItem) {
                if (!$nestedArrayItem instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem) {
                    continue;
                }
                $variable = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Variable($variableName . ($i === 1 ? '' : $i));
                $itemsStaticType = $this->getStaticType($nestedArrayItem->value);
                $paramAndArgs[] = new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\ParamAndArg($variable, $itemsStaticType);
                ++$i;
            }
        }
        return $paramAndArgs;
    }
    private function resolveItemStaticType(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_ $array, bool $isNestedArray) : \_PhpScoperb75b35f52b74\PHPStan\Type\Type
    {
        $staticTypes = [];
        if (!$isNestedArray) {
            foreach ($array->items as $arrayItem) {
                if (!$arrayItem instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem) {
                    continue;
                }
                $staticTypes[] = $this->getStaticType($arrayItem->value);
            }
        }
        return $this->typeFactory->createMixedPassedOrUnionType($staticTypes);
    }
    /**
     * @return ParamAndArg[]
     */
    private function collectParamAndArgsFromNonNestedArray(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_ $array, string $variableName, \_PhpScoperb75b35f52b74\PHPStan\Type\Type $itemsStaticType) : array
    {
        $i = 1;
        $paramAndArgs = [];
        foreach ($array->items as $arrayItem) {
            if (!$arrayItem instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem) {
                continue;
            }
            $variable = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Variable($variableName . ($i === 1 ? '' : $i));
            $paramAndArgs[] = new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\ParamAndArg($variable, $itemsStaticType);
            ++$i;
            if (!$arrayItem->value instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_) {
                break;
            }
        }
        return $paramAndArgs;
    }
    /**
     * @param ParamAndArg[] $paramAndArgs
     * @return Param[]
     */
    private function createParams(array $paramAndArgs) : array
    {
        $params = [];
        foreach ($paramAndArgs as $paramAndArg) {
            $param = new \_PhpScoperb75b35f52b74\PhpParser\Node\Param($paramAndArg->getVariable());
            $this->setTypeIfNotNull($paramAndArg, $param);
            $params[] = $param;
        }
        return $params;
    }
    private function createParamTagNode(string $name, \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode) : \_PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareParamTagValueNode
    {
        return new \_PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareParamTagValueNode($typeNode, \false, '$' . $name, '', \false);
    }
    private function setTypeIfNotNull(\_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\ParamAndArg $paramAndArg, \_PhpScoperb75b35f52b74\PhpParser\Node\Param $param) : void
    {
        $staticType = $paramAndArg->getType();
        if ($staticType === null) {
            return;
        }
        if ($staticType instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\UnionType) {
            return;
        }
        $phpNodeType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($staticType);
        if ($phpNodeType === null) {
            return;
        }
        $param->type = $phpNodeType;
    }
}
