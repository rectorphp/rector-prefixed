<?php

declare (strict_types=1);
namespace Rector\DeadCode\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\NodeManipulator\CallDefaultParamValuesResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\DeadCode\Rector\MethodCall\RemoveDefaultArgumentValueRector\RemoveDefaultArgumentValueRectorTest
 */
final class RemoveDefaultArgumentValueRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var CallDefaultParamValuesResolver
     */
    private $callDefaultParamValuesResolver;
    /**
     * @var ReflectionProvider
     */
    private $reflectionProvider;
    /**
     * @param \Rector\DeadCode\NodeManipulator\CallDefaultParamValuesResolver $callDefaultParamValuesResolver
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider
     */
    public function __construct($callDefaultParamValuesResolver, $reflectionProvider)
    {
        $this->callDefaultParamValuesResolver = $callDefaultParamValuesResolver;
        $this->reflectionProvider = $reflectionProvider;
    }
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
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\MethodCall::class, \PhpParser\Node\Expr\StaticCall::class, \PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param MethodCall|StaticCall|FuncCall $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $defaultValues = $this->callDefaultParamValuesResolver->resolveFromCall($node);
        $keysToRemove = $this->resolveKeysToRemove($node, $defaultValues);
        if ($keysToRemove === []) {
            return null;
        }
        foreach ($keysToRemove as $keyToRemove) {
            $this->nodeRemover->removeArg($node, $keyToRemove);
        }
        return $node;
    }
    /**
     * @param MethodCall|StaticCall|FuncCall $node
     */
    private function shouldSkip($node) : bool
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
        $name = new \PhpParser\Node\Name($functionName);
        if (!$this->reflectionProvider->hasFunction($name, null)) {
            return \false;
        }
        $reflectionFunction = $this->reflectionProvider->getFunction($name, null);
        // skip native functions, hard to analyze without stubs (stubs would make working with IDE non-practical)
        return $reflectionFunction->isBuiltin();
    }
    /**
     * @param StaticCall|MethodCall|FuncCall $node
     * @param Expr[]|mixed[] $defaultValues
     * @return int[]
     */
    private function resolveKeysToRemove($node, $defaultValues) : array
    {
        $keysToRemove = [];
        $keysToKeep = [];
        /** @var int $key */
        foreach ($node->args as $key => $arg) {
            if (!isset($defaultValues[$key])) {
                $keysToKeep[] = $key;
                continue;
            }
            if ($this->nodeComparator->areNodesEqual($defaultValues[$key], $arg->value)) {
                $keysToRemove[] = $key;
            } else {
                $keysToKeep[] = $key;
            }
        }
        if ($keysToRemove === []) {
            return [];
        }
        if ($keysToKeep === []) {
            /** @var int[] $keysToRemove */
            return $keysToRemove;
        }
        if (\max($keysToKeep) <= \max($keysToRemove)) {
            /** @var int[] $keysToRemove */
            return $keysToRemove;
        }
        return [];
    }
}
