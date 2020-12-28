<?php

declare (strict_types=1);
namespace Rector\CodingStyle\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Rector\AbstractRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\CodingStyle\Tests\Rector\ClassMethod\UnSpreadOperatorRector\UnSpreadOperatorRectorTest
 */
final class UnSpreadOperatorRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove spread operator', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run(...$array)
    {
    }

    public function execute(array $data)
    {
        $this->run(...$data);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(array $array)
    {
    }

    public function execute(array $data)
    {
        $this->run($data);
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
        return [\PhpParser\Node\Stmt\ClassMethod::class, \PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param ClassMethod|MethodCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($node instanceof \PhpParser\Node\Stmt\ClassMethod) {
            return $this->processUnspreadOperatorClassMethodParams($node);
        }
        return $this->processUnspreadOperatorMethodCallArgs($node);
    }
    private function processUnspreadOperatorClassMethodParams(\PhpParser\Node\Stmt\ClassMethod $classMethod) : ?\PhpParser\Node\Stmt\ClassMethod
    {
        $params = $classMethod->params;
        if ($params === []) {
            return null;
        }
        $spreadVariables = $this->getSpreadVariables($params);
        if ($spreadVariables === []) {
            return null;
        }
        foreach (\array_keys($spreadVariables) as $key) {
            $classMethod->params[$key]->variadic = \false;
            $classMethod->params[$key]->type = new \PhpParser\Node\Identifier('array');
        }
        return $classMethod;
    }
    private function processUnspreadOperatorMethodCallArgs(\PhpParser\Node\Expr\MethodCall $methodCall) : ?\PhpParser\Node\Expr\MethodCall
    {
        $args = $methodCall->args;
        if ($args === []) {
            return null;
        }
        $spreadVariables = $this->getSpreadVariables($args);
        if ($spreadVariables === []) {
            return null;
        }
        foreach (\array_keys($spreadVariables) as $key) {
            $methodCall->args[$key]->unpack = \false;
        }
        return $methodCall;
    }
    /**
     * @param Param[]|Arg[] $array
     * @return Param[]|Arg[]
     */
    private function getSpreadVariables(array $array) : array
    {
        $spreadVariables = [];
        foreach ($array as $key => $paramOrArg) {
            if ($paramOrArg instanceof \PhpParser\Node\Param) {
                if (!$paramOrArg->variadic) {
                    continue;
                }
                if ($paramOrArg->type !== null) {
                    continue;
                }
            }
            if ($paramOrArg instanceof \PhpParser\Node\Arg && !$paramOrArg->unpack) {
                continue;
            }
            $spreadVariables[$key] = $paramOrArg;
        }
        return $spreadVariables;
    }
}
