<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\CodingStyle\Rector\ClassMethod;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Closure;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Function_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Nop;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\CodingStyle\Tests\Rector\ClassMethod\NewlineBeforeNewAssignSetRector\NewlineBeforeNewAssignSetRectorTest
 */
final class NewlineBeforeNewAssignSetRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string|null
     */
    private $previousStmtVariableName;
    /**
     * @var string|null
     */
    private $previousPreviousStmtVariableName;
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Add extra space before new assign set', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $value = new Value;
        $value->setValue(5);
        $value2 = new Value;
        $value2->setValue(1);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $value = new Value;
        $value->setValue(5);

        $value2 = new Value;
        $value2->setValue(1);
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
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod::class, \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Function_::class, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Closure::class];
    }
    /**
     * @param ClassMethod|Function_|Closure $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        $this->reset();
        $hasChanged = \false;
        foreach ((array) $node->stmts as $key => $stmt) {
            $currentStmtVariableName = $this->resolveCurrentStmtVariableName($stmt);
            if ($this->shouldAddEmptyLine($currentStmtVariableName, $node, $key)) {
                $hasChanged = \true;
                // insert newline before
                $stmts = (array) $node->stmts;
                \array_splice($stmts, $key, 0, [new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Nop()]);
                $node->stmts = $stmts;
            }
            $this->previousPreviousStmtVariableName = $this->previousStmtVariableName;
            $this->previousStmtVariableName = $currentStmtVariableName;
        }
        return $hasChanged ? $node : null;
    }
    private function reset() : void
    {
        $this->previousStmtVariableName = null;
        $this->previousPreviousStmtVariableName = null;
    }
    private function resolveCurrentStmtVariableName(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt $stmt) : ?string
    {
        $stmt = $this->unwrapExpression($stmt);
        if ($stmt instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign || $stmt instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall) {
            if ($this->shouldSkipLeftVariable($stmt)) {
                return null;
            }
            if (!$stmt->var instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall && !$stmt->var instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall) {
                return $this->getName($stmt->var);
            }
        }
        return null;
    }
    /**
     * @param ClassMethod|Function_|Closure $node
     */
    private function shouldAddEmptyLine(?string $currentStmtVariableName, \_PhpScoperb75b35f52b74\PhpParser\Node $node, int $key) : bool
    {
        if (!$this->isNewVariableThanBefore($currentStmtVariableName)) {
            return \false;
        }
        // this is already empty line before
        return !$this->isPreceededByEmptyLine($node, $key);
    }
    /**
     * @param Assign|MethodCall $node
     */
    private function shouldSkipLeftVariable(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : bool
    {
        // local method call
        return $this->isVariableName($node->var, 'this');
    }
    private function isNewVariableThanBefore(?string $currentStmtVariableName) : bool
    {
        if ($this->previousPreviousStmtVariableName === null) {
            return \false;
        }
        if ($this->previousStmtVariableName === null) {
            return \false;
        }
        if ($currentStmtVariableName === null) {
            return \false;
        }
        if ($this->previousStmtVariableName !== $this->previousPreviousStmtVariableName) {
            return \false;
        }
        return $this->previousStmtVariableName !== $currentStmtVariableName;
    }
    /**
     * @param ClassMethod|Function_|Closure $node
     */
    private function isPreceededByEmptyLine(\_PhpScoperb75b35f52b74\PhpParser\Node $node, int $key) : bool
    {
        if ($node->stmts === null) {
            return \false;
        }
        $previousNode = $node->stmts[$key - 1];
        $currentNode = $node->stmts[$key];
        return \abs($currentNode->getLine() - $previousNode->getLine()) >= 2;
    }
}
