<?php

declare (strict_types=1);
namespace Rector\Naming\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\BooleanType;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\MethodNameResolver;
use Rector\Naming\NodeRenamer\MethodCallRenamer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\Naming\Rector\ClassMethod\MakeGetterClassMethodNameStartWithGetRector\MakeGetterClassMethodNameStartWithGetRectorTest
 */
final class MakeGetterClassMethodNameStartWithGetRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const GETTER_NAME_PATTERN = '#^(is|should|has|was|must|get|provide|__)#';
    /**
     * @var MethodNameResolver
     */
    private $methodNameResolver;
    /**
     * @var MethodCallRenamer
     */
    private $methodCallRenamer;
    /**
     * @param \Rector\Naming\Naming\MethodNameResolver $methodNameResolver
     * @param \Rector\Naming\NodeRenamer\MethodCallRenamer $methodCallRenamer
     */
    public function __construct($methodNameResolver, $methodCallRenamer)
    {
        $this->methodNameResolver = $methodNameResolver;
        $this->methodCallRenamer = $methodCallRenamer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change getter method names to start with get/provide', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var string
     */
    private $name;

    public function name(): string
    {
        return $this->name;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var string
     */
    private $name;

    public function getName(): string
    {
        return $this->name;
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
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if ($this->isAlreadyGetterNamedClassMethod($node)) {
            return null;
        }
        $getterClassMethodReturnedExpr = $this->matchGetterClassMethodReturnedExpr($node);
        if (!$getterClassMethodReturnedExpr instanceof \PhpParser\Node\Expr) {
            return null;
        }
        $getterMethodName = $this->methodNameResolver->resolveGetterFromReturnedExpr($getterClassMethodReturnedExpr);
        if ($getterMethodName === null) {
            return null;
        }
        if ($this->isName($node->name, $getterMethodName)) {
            return null;
        }
        $node->name = new \PhpParser\Node\Identifier($getterMethodName);
        $this->methodCallRenamer->updateClassMethodCalls($node, $getterMethodName);
        return $node;
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     */
    private function isAlreadyGetterNamedClassMethod($classMethod) : bool
    {
        return $this->isName($classMethod, self::GETTER_NAME_PATTERN);
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     */
    private function matchGetterClassMethodReturnedExpr($classMethod) : ?\PhpParser\Node\Expr
    {
        $stmts = (array) $classMethod->stmts;
        if (\count($stmts) !== 1) {
            return null;
        }
        $onlyStmt = $stmts[0] ?? null;
        if (!$onlyStmt instanceof \PhpParser\Node\Stmt\Return_) {
            return null;
        }
        if (!$onlyStmt->expr instanceof \PhpParser\Node\Expr\PropertyFetch) {
            return null;
        }
        $propertyStaticType = $this->getStaticType($onlyStmt->expr);
        // is handled by boolish Rector → skip here
        if ($propertyStaticType instanceof \PHPStan\Type\BooleanType) {
            return null;
        }
        return $onlyStmt->expr;
    }
}
