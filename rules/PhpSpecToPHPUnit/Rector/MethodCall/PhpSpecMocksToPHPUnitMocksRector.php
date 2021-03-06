<?php

declare (strict_types=1);
namespace Rector\PhpSpecToPHPUnit\Rector\MethodCall;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Error;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Php\TypeAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpSpecToPHPUnit\PhpSpecMockCollector;
use Rector\PhpSpecToPHPUnit\Rector\AbstractPhpSpecToPHPUnitRector;
/**
 * @see \Rector\Tests\PhpSpecToPHPUnit\Rector\Variable\PhpSpecToPHPUnitRector\PhpSpecToPHPUnitRectorTest
 */
final class PhpSpecMocksToPHPUnitMocksRector extends \Rector\PhpSpecToPHPUnit\Rector\AbstractPhpSpecToPHPUnitRector
{
    /**
     * @var PhpSpecMockCollector
     */
    private $phpSpecMockCollector;
    /**
     * @var TypeAnalyzer
     */
    private $typeAnalyzer;
    /**
     * @param \Rector\PhpSpecToPHPUnit\PhpSpecMockCollector $phpSpecMockCollector
     * @param \Rector\Core\Php\TypeAnalyzer $typeAnalyzer
     */
    public function __construct($phpSpecMockCollector, $typeAnalyzer)
    {
        $this->phpSpecMockCollector = $phpSpecMockCollector;
        $this->typeAnalyzer = $typeAnalyzer;
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\ClassMethod::class, \PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param ClassMethod|MethodCall $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (!$this->isInPhpSpecBehavior($node)) {
            return null;
        }
        if ($node instanceof \PhpParser\Node\Stmt\ClassMethod) {
            // public = tests, protected = internal, private = own (no framework magic)
            if ($node->isPrivate()) {
                return null;
            }
            $this->processMethodParamsToMocks($node);
            return $node;
        }
        return $this->processMethodCall($node);
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     */
    private function processMethodParamsToMocks($classMethod) : void
    {
        // remove params and turn them to instances
        $assigns = [];
        foreach ($classMethod->params as $param) {
            if (!$param->type instanceof \PhpParser\Node\Name) {
                throw new \Rector\Core\Exception\ShouldNotHappenException();
            }
            $createMockCall = $this->createCreateMockCall($param, $param->type);
            if ($createMockCall !== null) {
                $assigns[] = $createMockCall;
            }
        }
        // remove all params
        $classMethod->params = [];
        $classMethod->stmts = \array_merge($assigns, (array) $classMethod->stmts);
    }
    /**
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     */
    private function processMethodCall($methodCall) : ?\PhpParser\Node\Expr\MethodCall
    {
        if ($this->isName($methodCall->name, 'shouldBeCalled')) {
            if (!$methodCall->var instanceof \PhpParser\Node\Expr\MethodCall) {
                throw new \Rector\Core\Exception\ShouldNotHappenException();
            }
            $mockMethodName = $this->getName($methodCall->var->name);
            if ($mockMethodName === null) {
                throw new \Rector\Core\Exception\ShouldNotHappenException();
            }
            $expectedArg = $methodCall->var->args[0]->value ?? null;
            $methodCall->var->name = new \PhpParser\Node\Identifier('expects');
            $thisOnceMethodCall = $this->nodeFactory->createLocalMethodCall('atLeastOnce');
            $methodCall->var->args = [new \PhpParser\Node\Arg($thisOnceMethodCall)];
            $methodCall->name = new \PhpParser\Node\Identifier('method');
            $methodCall->args = [new \PhpParser\Node\Arg(new \PhpParser\Node\Scalar\String_($mockMethodName))];
            if ($expectedArg !== null) {
                return $this->appendWithMethodCall($methodCall, $expectedArg);
            }
            return $methodCall;
        }
        return null;
    }
    /**
     * Variable or property fetch, based on number of present params in whole class
     * @param \PhpParser\Node\Param $param
     * @param \PhpParser\Node\Name $name
     */
    private function createCreateMockCall($param, $name) : ?\PhpParser\Node\Stmt\Expression
    {
        /** @var Class_ $classLike */
        $classLike = $param->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        $classMocks = $this->phpSpecMockCollector->resolveClassMocksFromParam($classLike);
        $variable = $this->getName($param->var);
        $method = $param->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::METHOD_NAME);
        $methodsWithWThisMock = $classMocks[$variable];
        if ($param->var instanceof \PhpParser\Node\Expr\Error) {
            return null;
        }
        // single use: "$mock = $this->createMock()"
        if (!$this->phpSpecMockCollector->isVariableMockInProperty($param->var)) {
            return $this->createNewMockVariableAssign($param, $name);
        }
        $reversedMethodsWithThisMock = \array_flip($methodsWithWThisMock);
        // first use of many: "$this->mock = $this->createMock()"
        if ($reversedMethodsWithThisMock[$method] === 0) {
            return $this->createPropertyFetchMockVariableAssign($param, $name);
        }
        return null;
    }
    /**
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PhpParser\Node\Expr $expr
     */
    private function appendWithMethodCall($methodCall, $expr) : \PhpParser\Node\Expr\MethodCall
    {
        $withMethodCall = new \PhpParser\Node\Expr\MethodCall($methodCall, 'with');
        if ($expr instanceof \PhpParser\Node\Expr\StaticCall) {
            if ($this->isName($expr->class, '*Argument')) {
                if ($this->isName($expr->name, 'any')) {
                    // no added value having this method
                    return $methodCall;
                }
                if ($this->isName($expr->name, 'type')) {
                    $expr = $this->createIsTypeOrIsInstanceOf($expr);
                }
            }
        } else {
            $newExpr = $this->nodeFactory->createLocalMethodCall('equalTo');
            $newExpr->args = [new \PhpParser\Node\Arg($expr)];
            $expr = $newExpr;
        }
        $withMethodCall->args = [new \PhpParser\Node\Arg($expr)];
        return $withMethodCall;
    }
    /**
     * @param \PhpParser\Node\Param $param
     * @param \PhpParser\Node\Name $name
     */
    private function createNewMockVariableAssign($param, $name) : \PhpParser\Node\Stmt\Expression
    {
        $methodCall = $this->nodeFactory->createLocalMethodCall('createMock');
        $methodCall->args[] = new \PhpParser\Node\Arg(new \PhpParser\Node\Expr\ClassConstFetch($name, 'class'));
        $assign = new \PhpParser\Node\Expr\Assign($param->var, $methodCall);
        $assignExpression = new \PhpParser\Node\Stmt\Expression($assign);
        // add @var doc comment
        $varDoc = $this->createMockVarDoc($param, $name);
        $assignExpression->setDocComment(new \PhpParser\Comment\Doc($varDoc));
        return $assignExpression;
    }
    /**
     * @param \PhpParser\Node\Param $param
     * @param \PhpParser\Node\Name $name
     */
    private function createPropertyFetchMockVariableAssign($param, $name) : \PhpParser\Node\Stmt\Expression
    {
        $variable = $this->getName($param->var);
        if ($variable === null) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        $propertyFetch = new \PhpParser\Node\Expr\PropertyFetch(new \PhpParser\Node\Expr\Variable('this'), $variable);
        $methodCall = $this->nodeFactory->createLocalMethodCall('createMock');
        $methodCall->args[] = new \PhpParser\Node\Arg(new \PhpParser\Node\Expr\ClassConstFetch($name, 'class'));
        $assign = new \PhpParser\Node\Expr\Assign($propertyFetch, $methodCall);
        return new \PhpParser\Node\Stmt\Expression($assign);
    }
    /**
     * @param \PhpParser\Node\Expr\StaticCall $staticCall
     */
    private function createIsTypeOrIsInstanceOf($staticCall) : \PhpParser\Node\Expr\MethodCall
    {
        $type = $this->valueResolver->getValue($staticCall->args[0]->value);
        $name = $this->typeAnalyzer->isPhpReservedType($type) ? 'isType' : 'isInstanceOf';
        return $this->nodeFactory->createLocalMethodCall($name, $staticCall->args);
    }
    /**
     * @param \PhpParser\Node\Param $param
     * @param \PhpParser\Node\Name $name
     */
    private function createMockVarDoc($param, $name) : string
    {
        $paramType = (string) ($name->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NAME) ?: $name);
        $variableName = $this->getName($param->var);
        if ($variableName === null) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        return \sprintf('/** @var %s|\\%s $%s */', $paramType, 'PHPUnit\\Framework\\MockObject\\MockObject', $variableName);
    }
}
