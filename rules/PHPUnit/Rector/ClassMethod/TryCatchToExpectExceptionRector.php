<?php

declare (strict_types=1);
namespace Rector\PHPUnit\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\TryCatch;
use Rector\Core\Rector\AbstractRector;
use Rector\PHPUnit\NodeAnalyzer\TestsNodeAnalyzer;
use Rector\PHPUnit\NodeFactory\ExpectExceptionCodeFactory;
use Rector\PHPUnit\NodeFactory\ExpectExceptionFactory;
use Rector\PHPUnit\NodeFactory\ExpectExceptionMessageFactory;
use Rector\PHPUnit\NodeFactory\ExpectExceptionMessageRegExpFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\PHPUnit\Rector\ClassMethod\TryCatchToExpectExceptionRector\TryCatchToExpectExceptionRectorTest
 */
final class TryCatchToExpectExceptionRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var TestsNodeAnalyzer
     */
    private $testsNodeAnalyzer;
    /**
     * @var ExpectExceptionCodeFactory
     */
    private $expectExceptionCodeFactory;
    /**
     * @var ExpectExceptionMessageRegExpFactory
     */
    private $expectExceptionMessageRegExpFactory;
    /**
     * @var ExpectExceptionFactory
     */
    private $expectExceptionFactory;
    /**
     * @var ExpectExceptionMessageFactory
     */
    private $expectExceptionMessageFactory;
    /**
     * @param \Rector\PHPUnit\NodeAnalyzer\TestsNodeAnalyzer $testsNodeAnalyzer
     * @param \Rector\PHPUnit\NodeFactory\ExpectExceptionCodeFactory $expectExceptionCodeFactory
     * @param \Rector\PHPUnit\NodeFactory\ExpectExceptionMessageRegExpFactory $expectExceptionMessageRegExpFactory
     * @param \Rector\PHPUnit\NodeFactory\ExpectExceptionFactory $expectExceptionFactory
     * @param \Rector\PHPUnit\NodeFactory\ExpectExceptionMessageFactory $expectExceptionMessageFactory
     */
    public function __construct($testsNodeAnalyzer, $expectExceptionCodeFactory, $expectExceptionMessageRegExpFactory, $expectExceptionFactory, $expectExceptionMessageFactory)
    {
        $this->testsNodeAnalyzer = $testsNodeAnalyzer;
        $this->expectExceptionCodeFactory = $expectExceptionCodeFactory;
        $this->expectExceptionMessageRegExpFactory = $expectExceptionMessageRegExpFactory;
        $this->expectExceptionFactory = $expectExceptionFactory;
        $this->expectExceptionMessageFactory = $expectExceptionMessageFactory;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns try/catch to expectException() call', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
try {
    $someService->run();
} catch (Throwable $exception) {
    $this->assertInstanceOf(RuntimeException::class, $e);
    $this->assertContains('There was an error executing the following script', $e->getMessage());
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$this->expectException(RuntimeException::class);
$this->expectExceptionMessage('There was an error executing the following script');
$someService->run();
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
        if (!$this->testsNodeAnalyzer->isInTestClass($node)) {
            return null;
        }
        if ($node->stmts === null) {
            return null;
        }
        $proccesed = [];
        foreach ($node->stmts as $key => $stmt) {
            if (!$stmt instanceof \PhpParser\Node\Stmt\TryCatch) {
                continue;
            }
            $proccesed = $this->processTryCatch($stmt);
            if ($proccesed === null) {
                continue;
            }
            /** @var int $key */
            $this->nodeRemover->removeStmt($node, $key);
        }
        $node->stmts = \array_merge($node->stmts, (array) $proccesed);
        return $node;
    }
    /**
     * @return Expression[]|null
     * @param \PhpParser\Node\Stmt\TryCatch $tryCatch
     */
    private function processTryCatch($tryCatch) : ?array
    {
        $exceptionVariable = $this->matchSingleExceptionVariable($tryCatch);
        if (!$exceptionVariable instanceof \PhpParser\Node\Expr\Variable) {
            return null;
        }
        // we look for:
        // - instance of $exceptionVariableName
        // - assert same string to $exceptionVariableName->getMessage()
        // - assert same string to $exceptionVariableName->getCode()
        $newMethodCalls = [];
        foreach ($tryCatch->catches[0]->stmts as $catchedStmt) {
            // not a match
            if (!$catchedStmt instanceof \PhpParser\Node\Stmt\Expression) {
                return null;
            }
            if (!$catchedStmt->expr instanceof \PhpParser\Node\Expr\MethodCall) {
                continue;
            }
            $methodCallNode = $catchedStmt->expr;
            $newMethodCalls[] = $this->expectExceptionMessageFactory->create($methodCallNode, $exceptionVariable);
            $newMethodCalls[] = $this->expectExceptionFactory->create($methodCallNode, $exceptionVariable);
            $newMethodCalls[] = $this->expectExceptionCodeFactory->create($methodCallNode, $exceptionVariable);
            $newMethodCalls[] = $this->expectExceptionMessageRegExpFactory->create($methodCallNode, $exceptionVariable);
        }
        $newMethodCalls = \array_filter($newMethodCalls);
        $newExpressions = $this->wrapInExpressions($newMethodCalls);
        // return all statements
        foreach ($tryCatch->stmts as $stmt) {
            if (!$stmt instanceof \PhpParser\Node\Stmt\Expression) {
                return null;
            }
            $newExpressions[] = $stmt;
        }
        return $newExpressions;
    }
    /**
     * @param \PhpParser\Node\Stmt\TryCatch $tryCatch
     */
    private function matchSingleExceptionVariable($tryCatch) : ?\PhpParser\Node\Expr\Variable
    {
        if (\count($tryCatch->catches) !== 1) {
            return null;
        }
        return $tryCatch->catches[0]->var;
    }
    /**
     * @param MethodCall[] $methodCalls
     * @return Expression[]
     */
    private function wrapInExpressions($methodCalls) : array
    {
        $expressions = [];
        foreach ($methodCalls as $methodCall) {
            $expressions[] = new \PhpParser\Node\Stmt\Expression($methodCall);
        }
        return $expressions;
    }
}
