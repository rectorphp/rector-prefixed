<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Naming\Rector\ClassMethod;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\PropertyFetch;
use _PhpScoperb75b35f52b74\PhpParser\Node\Identifier;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Return_;
use _PhpScoperb75b35f52b74\PHPStan\Type\BooleanType;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Rector\Naming\Naming\MethodNameResolver;
use _PhpScoperb75b35f52b74\Rector\Naming\NodeRenamer\MethodCallRenamer;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Naming\Tests\Rector\ClassMethod\MakeIsserClassMethodNameStartWithIsRector\MakeIsserClassMethodNameStartWithIsRectorTest
 */
final class MakeIsserClassMethodNameStartWithIsRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @see https://regex101.com/r/Hc73ar/1
     * @var string
     */
    private const ISSER_NAME_REGEX = '#^(is|has|was|must|does|have|should|__)#';
    /**
     * @var MethodNameResolver
     */
    private $methodNameResolver;
    /**
     * @var MethodCallRenamer
     */
    private $methodCallRenamer;
    public function __construct(\_PhpScoperb75b35f52b74\Rector\Naming\Naming\MethodNameResolver $methodNameResolver, \_PhpScoperb75b35f52b74\Rector\Naming\NodeRenamer\MethodCallRenamer $methodCallRenamer)
    {
        $this->methodNameResolver = $methodNameResolver;
        $this->methodCallRenamer = $methodCallRenamer;
    }
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change is method names to start with is/has/was', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var bool
     */
    private $isActive = false;

    public function getIsActive()
    {
        return $this->isActive;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var bool
     */
    private $isActive = false;

    public function isActive()
    {
        return $this->isActive;
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
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if ($this->isAlreadyIsserNamedClassMethod($node)) {
            return null;
        }
        $getterClassMethodReturnedExpr = $this->matchIsserClassMethodReturnedExpr($node);
        if ($getterClassMethodReturnedExpr === null) {
            return null;
        }
        $isserMethodName = $this->methodNameResolver->resolveIsserFromReturnedExpr($getterClassMethodReturnedExpr);
        if ($isserMethodName === null) {
            return null;
        }
        if ($this->isName($node->name, $isserMethodName)) {
            return null;
        }
        $node->name = new \_PhpScoperb75b35f52b74\PhpParser\Node\Identifier($isserMethodName);
        $this->methodCallRenamer->updateClassMethodCalls($node, $isserMethodName);
        return $node;
    }
    private function isAlreadyIsserNamedClassMethod(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        return $this->isName($classMethod, self::ISSER_NAME_REGEX);
    }
    private function matchIsserClassMethodReturnedExpr(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod $classMethod) : ?\_PhpScoperb75b35f52b74\PhpParser\Node\Expr
    {
        $stmts = (array) $classMethod->stmts;
        if (\count($stmts) !== 1) {
            return null;
        }
        $onlyStmt = $stmts[0] ?? null;
        if (!$onlyStmt instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Return_) {
            return null;
        }
        if (!$onlyStmt->expr instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\PropertyFetch) {
            return null;
        }
        $propertyStaticType = $this->getStaticType($onlyStmt->expr);
        if (!$propertyStaticType instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\BooleanType) {
            return null;
        }
        return $onlyStmt->expr;
    }
}
