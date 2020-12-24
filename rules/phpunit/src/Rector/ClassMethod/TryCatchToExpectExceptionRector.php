<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\PHPUnit\Rector\ClassMethod;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Variable;
use _PhpScopere8e811afab72\PhpParser\Node\Identifier;
use _PhpScopere8e811afab72\PhpParser\Node\Scalar\String_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\TryCatch;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractPHPUnitRector;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\PHPUnit\Tests\Rector\ClassMethod\TryCatchToExpectExceptionRector\TryCatchToExpectExceptionRectorTest
 */
final class TryCatchToExpectExceptionRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractPHPUnitRector
{
    /**
     * @var Expression[]
     */
    private $newExpressions = [];
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns try/catch to expectException() call', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
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
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if (!$this->isInTestClass($node)) {
            return null;
        }
        if (!$node->stmts) {
            return null;
        }
        $proccesed = [];
        foreach ($node->stmts as $key => $stmt) {
            if (!$stmt instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\TryCatch) {
                continue;
            }
            $proccesed = $this->processTryCatch($stmt);
            if ($proccesed === null) {
                continue;
            }
            /** @var int $key */
            $this->removeStmt($node, $key);
        }
        $node->stmts = \array_merge((array) $node->stmts, (array) $proccesed);
        return $node;
    }
    /**
     * @return Expression[]|null
     */
    private function processTryCatch(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\TryCatch $tryCatch) : ?array
    {
        if (\count((array) $tryCatch->catches) !== 1) {
            return null;
        }
        $this->newExpressions = [];
        $exceptionVariable = $tryCatch->catches[0]->var;
        if ($exceptionVariable === null) {
            return null;
        }
        // we look for:
        // - instance of $exceptionVariableName
        // - assert same string to $exceptionVariableName->getMessage()
        // - assert same string to $exceptionVariableName->getCode()
        foreach ($tryCatch->catches[0]->stmts as $catchedStmt) {
            // not a match
            if (!$catchedStmt instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression) {
                return null;
            }
            if (!$catchedStmt->expr instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
                continue;
            }
            $methodCallNode = $catchedStmt->expr;
            $this->processAssertInstanceOf($methodCallNode, $exceptionVariable);
            $this->processExceptionMessage($methodCallNode, $exceptionVariable);
            $this->processExceptionCode($methodCallNode, $exceptionVariable);
            $this->processExceptionMessageContains($methodCallNode, $exceptionVariable);
        }
        // return all statements
        foreach ($tryCatch->stmts as $stmt) {
            if (!$stmt instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression) {
                return null;
            }
            $this->newExpressions[] = $stmt;
        }
        return $this->newExpressions;
    }
    private function processAssertInstanceOf(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall, \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable $variable) : void
    {
        if (!$this->isLocalMethodCallNamed($methodCall, 'assertInstanceOf')) {
            return;
        }
        /** @var MethodCall $methodCall */
        $argumentVariableName = $this->getName($methodCall->args[1]->value);
        if ($argumentVariableName === null) {
            return;
        }
        // is na exception variable
        if (!$this->isName($variable, $argumentVariableName)) {
            return;
        }
        $this->newExpressions[] = new \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression(new \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall($methodCall->var, 'expectException', [$methodCall->args[0]]));
    }
    private function processExceptionMessage(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall, \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable $exceptionVariable) : void
    {
        if (!$this->isLocalMethodCallsNamed($methodCall, ['assertSame', 'assertEquals'])) {
            return;
        }
        $secondArgument = $methodCall->args[1]->value;
        if (!$secondArgument instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
            return;
        }
        if (!$this->areNodesEqual($secondArgument->var, $exceptionVariable)) {
            return;
        }
        if (!$this->isName($secondArgument->name, 'getMessage')) {
            return;
        }
        $this->newExpressions[] = $this->renameMethodCallAndKeepFirstArgument($methodCall, 'expectExceptionMessage');
    }
    private function processExceptionCode(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall, \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable $exceptionVariable) : void
    {
        if (!$this->isLocalMethodCallsNamed($methodCall, ['assertSame', 'assertEquals'])) {
            return;
        }
        $secondArgument = $methodCall->args[1]->value;
        if (!$secondArgument instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
            return;
        }
        // looking for "$exception->getMessage()"
        if (!$this->areNamesEqual($secondArgument->var, $exceptionVariable)) {
            return;
        }
        if (!$this->isName($secondArgument->name, 'getCode')) {
            return;
        }
        $this->newExpressions[] = $this->renameMethodCallAndKeepFirstArgument($methodCall, 'expectExceptionCode');
    }
    private function processExceptionMessageContains(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall, \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable $exceptionVariable) : void
    {
        if (!$this->isLocalMethodCallNamed($methodCall, 'assertContains')) {
            return;
        }
        $secondArgument = $methodCall->args[1]->value;
        if (!$secondArgument instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
            return;
        }
        // looking for "$exception->getMessage()"
        if (!$this->areNodesEqual($secondArgument->var, $exceptionVariable)) {
            return;
        }
        if (!$this->isName($secondArgument->name, 'getMessage')) {
            return;
        }
        $expression = $this->renameMethodCallAndKeepFirstArgument($methodCall, 'expectExceptionMessageRegExp');
        /** @var MethodCall $methodCall */
        $methodCall = $expression->expr;
        // put regex between "#...#" to create match
        if ($methodCall->args[0]->value instanceof \_PhpScopere8e811afab72\PhpParser\Node\Scalar\String_) {
            /** @var String_ $oldString */
            $oldString = $methodCall->args[0]->value;
            $methodCall->args[0]->value = new \_PhpScopere8e811afab72\PhpParser\Node\Scalar\String_('#' . \preg_quote($oldString->value, '#') . '#');
        }
        $this->newExpressions[] = $expression;
    }
    private function renameMethodCallAndKeepFirstArgument(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall, string $methodName) : \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression
    {
        $methodCall->name = new \_PhpScopere8e811afab72\PhpParser\Node\Identifier($methodName);
        foreach (\array_keys($methodCall->args) as $i) {
            // keep first arg
            if ($i === 0) {
                continue;
            }
            unset($methodCall->args[$i]);
        }
        return new \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression($methodCall);
    }
}
