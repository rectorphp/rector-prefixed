<?php

declare (strict_types=1);
namespace Rector\Symfony4\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://symfony.com/blog/new-in-symfony-4-3-simpler-event-dispatching
 * @see \Rector\Symfony4\Tests\Rector\MethodCall\MakeDispatchFirstArgumentEventRector\MakeDispatchFirstArgumentEventRectorTest
 */
final class MakeDispatchFirstArgumentEventRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Make event object a first argument of dispatch() method, event name as second', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SomeClass
{
    public function run(EventDispatcherInterface $eventDispatcher)
    {
        $eventDispatcher->dispatch('event_name', new Event());
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SomeClass
{
    public function run(EventDispatcherInterface $eventDispatcher)
    {
        $eventDispatcher->dispatch(new Event(), 'event_name');
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
        return [\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param MethodCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $firstArgumentValue = $node->args[0]->value;
        if ($this->isStringOrUnionStringOnlyType($firstArgumentValue)) {
            return $this->refactorStringArgument($node);
        }
        $secondArgumentValue = $node->args[1]->value;
        if ($secondArgumentValue instanceof \PhpParser\Node\Expr\FuncCall) {
            return $this->refactorGetCallFuncCall($node, $secondArgumentValue, $firstArgumentValue);
        }
        return null;
    }
    private function shouldSkip(\PhpParser\Node\Expr\MethodCall $methodCall) : bool
    {
        if (!$this->isObjectType($methodCall->var, 'Symfony\\Contracts\\EventDispatcher\\EventDispatcherInterface')) {
            return \true;
        }
        if (!$this->isName($methodCall->name, 'dispatch')) {
            return \true;
        }
        return !isset($methodCall->args[1]);
    }
    private function refactorStringArgument(\PhpParser\Node\Expr\MethodCall $methodCall) : \PhpParser\Node\Expr\MethodCall
    {
        // swap arguments
        [$methodCall->args[0], $methodCall->args[1]] = [$methodCall->args[1], $methodCall->args[0]];
        if ($this->isEventNameSameAsEventObjectClass($methodCall)) {
            unset($methodCall->args[1]);
        }
        return $methodCall;
    }
    private function refactorGetCallFuncCall(\PhpParser\Node\Expr\MethodCall $methodCall, \PhpParser\Node\Expr\FuncCall $funcCall, \PhpParser\Node\Expr $expr) : ?\PhpParser\Node\Expr\MethodCall
    {
        if ($this->isName($funcCall, 'get_class')) {
            $getClassArgumentValue = $funcCall->args[0]->value;
            if ($this->areNodesEqual($expr, $getClassArgumentValue)) {
                unset($methodCall->args[1]);
                return $methodCall;
            }
        }
        return null;
    }
    /**
     * Is the event name just `::class`?
     * We can remove it
     */
    private function isEventNameSameAsEventObjectClass(\PhpParser\Node\Expr\MethodCall $methodCall) : bool
    {
        if (!$methodCall->args[1]->value instanceof \PhpParser\Node\Expr\ClassConstFetch) {
            return \false;
        }
        $classConst = $this->getValue($methodCall->args[1]->value);
        $eventStaticType = $this->getStaticType($methodCall->args[0]->value);
        if (!$eventStaticType instanceof \PHPStan\Type\ObjectType) {
            return \false;
        }
        return $classConst === $eventStaticType->getClassName();
    }
}
