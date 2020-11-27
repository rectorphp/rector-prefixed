<?php

declare (strict_types=1);
namespace Rector\DeadCode\Rector\MethodCall;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Name;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use ReflectionFunction;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DeadCode\Tests\Rector\MethodCall\RemoveDefaultArgumentValueRector\RemoveDefaultArgumentValueRectorTest
 */
final class RemoveDefaultArgumentValueRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove argument value, if it is the same as default value', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->runWithDefault([]);
        $card = self::runWithStaticDefault([]);
    }

    public function runWithDefault($items = [])
    {
        return $items;
    }

    public function runStaticWithDefault($cards = [])
    {
        return $cards;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->runWithDefault();
        $card = self::runWithStaticDefault();
    }

    public function runWithDefault($items = [])
    {
        return $items;
    }

    public function runStaticWithDefault($cards = [])
    {
        return $cards;
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\MethodCall::class, \PhpParser\Node\Expr\StaticCall::class, \PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param MethodCall|StaticCall|FuncCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $defaultValues = $this->resolveDefaultValuesFromCall($node);
        $keysToRemove = $this->resolveKeysToRemove($node, $defaultValues);
        if ($keysToRemove === []) {
            return null;
        }
        foreach ($keysToRemove as $keyToRemove) {
            $this->removeArg($node, $keyToRemove);
        }
        return $node;
    }
    /**
     * @param MethodCall|StaticCall|FuncCall $node
     */
    private function shouldSkip(\PhpParser\Node $node) : bool
    {
        if ($node->args === []) {
            return \true;
        }
        if (!$node instanceof \PhpParser\Node\Expr\FuncCall) {
            return \false;
        }
        if (!$node->name instanceof \PhpParser\Node\Name) {
            return \true;
        }
        $functionName = $this->getName($node);
        if ($functionName === null) {
            return \false;
        }
        if (!\function_exists($functionName)) {
            return \false;
        }
        $reflectionFunction = new \ReflectionFunction($functionName);
        // skip native functions, hard to analyze without stubs (stubs would make working with IDE non-practical)
        return $reflectionFunction->isInternal();
    }
    /**
     * @param StaticCall|FuncCall|MethodCall $node
     * @return Node[]
     */
    private function resolveDefaultValuesFromCall(\PhpParser\Node $node) : array
    {
        $nodeName = $this->resolveNodeName($node);
        if ($nodeName === null) {
            return [];
        }
        if ($node instanceof \PhpParser\Node\Expr\FuncCall) {
            return $this->resolveFuncCallDefaultParamValues($nodeName);
        }
        /** @var string|null $className */
        $className = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        // anonymous class
        if ($className === null) {
            return [];
        }
        $classMethodNode = $this->nodeRepository->findClassMethod($className, $nodeName);
        if ($classMethodNode !== null) {
            return $this->resolveDefaultParamValuesFromFunctionLike($classMethodNode);
        }
        return [];
    }
    /**
     * @param StaticCall|MethodCall|FuncCall $node
     * @param Expr[]|mixed[] $defaultValues
     * @return int[]
     */
    private function resolveKeysToRemove(\PhpParser\Node $node, array $defaultValues) : array
    {
        $keysToRemove = [];
        $keysToKeep = [];
        /** @var int $key */
        foreach ($node->args as $key => $arg) {
            if (!isset($defaultValues[$key])) {
                $keysToKeep[] = $key;
                continue;
            }
            if ($this->areNodesEqual($defaultValues[$key], $arg->value)) {
                $keysToRemove[] = $key;
            } else {
                $keysToKeep[] = $key;
            }
        }
        if ($keysToRemove === []) {
            return [];
        }
        if ($keysToKeep !== [] && \max($keysToKeep) > \max($keysToRemove)) {
            return [];
        }
        /** @var int[] $keysToRemove */
        return $keysToRemove;
    }
    /**
     * @param StaticCall|FuncCall|MethodCall $node
     */
    private function resolveNodeName(\PhpParser\Node $node) : ?string
    {
        if ($node instanceof \PhpParser\Node\Expr\FuncCall) {
            return $this->getName($node);
        }
        return $this->getName($node->name);
    }
    /**
     * @return Node[]|Expr[]
     */
    private function resolveFuncCallDefaultParamValues(string $nodeName) : array
    {
        $functionNode = $this->nodeRepository->findFunction($nodeName);
        if ($functionNode !== null) {
            return $this->resolveDefaultParamValuesFromFunctionLike($functionNode);
        }
        // non existing function
        if (!\function_exists($nodeName)) {
            return [];
        }
        $reflectionFunction = new \ReflectionFunction($nodeName);
        if ($reflectionFunction->isUserDefined()) {
            $defaultValues = [];
            foreach ($reflectionFunction->getParameters() as $key => $reflectionParameter) {
                if ($reflectionParameter->isDefaultValueAvailable()) {
                    $defaultValues[$key] = \PhpParser\BuilderHelpers::normalizeValue($reflectionParameter->getDefaultValue());
                }
            }
            return $defaultValues;
        }
        return [];
    }
    /**
     * @return Node[]
     */
    private function resolveDefaultParamValuesFromFunctionLike(\PhpParser\Node\FunctionLike $functionLike) : array
    {
        $defaultValues = [];
        foreach ($functionLike->getParams() as $key => $param) {
            if ($param->default === null) {
                continue;
            }
            $defaultValues[$key] = $param->default;
        }
        return $defaultValues;
    }
}