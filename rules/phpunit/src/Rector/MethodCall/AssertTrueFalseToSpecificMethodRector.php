<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Arg;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Empty_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Identifier;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractPHPUnitRector;
use _PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\PHPUnit\Tests\Rector\MethodCall\AssertTrueFalseToSpecificMethodRector\AssertTrueFalseToSpecificMethodRectorTest
 */
final class AssertTrueFalseToSpecificMethodRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractPHPUnitRector
{
    /**
     * @var FunctionNameWithAssertMethods[]
     */
    private $functionNameWithAssertMethods = [];
    public function __construct()
    {
        $this->functionNameWithAssertMethods = [new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('is_readable', 'assertIsReadable', 'assertNotIsReadable'), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('array_key_exists', 'assertArrayHasKey', 'assertArrayNotHasKey'), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('array_search', 'assertContains', 'assertNotContains'), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('in_array', 'assertContains', 'assertNotContains'), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('empty', 'assertEmpty', 'assertNotEmpty'), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('file_exists', 'assertFileExists', 'assertFileNotExists'), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('is_dir', 'assertDirectoryExists', 'assertDirectoryNotExists'), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('is_infinite', 'assertInfinite', 'assertFinite'), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('is_null', 'assertNull', 'assertNotNull'), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('is_writable', 'assertIsWritable', 'assertNotIsWritable'), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('is_nan', 'assertNan', ''), new \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods('is_a', 'assertInstanceOf', 'assertNotInstanceOf')];
    }
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns true/false comparisons to their method name alternatives in PHPUnit TestCase when possible', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('$this->assertTrue(is_readable($readmeFile), "message");', '$this->assertIsReadable($readmeFile, "message");')]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall::class, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if (!$this->isPHPUnitMethodNames($node, ['assertTrue', 'assertFalse', 'assertNotTrue', 'assertNotFalse'])) {
            return null;
        }
        if (!isset($node->args[0])) {
            return null;
        }
        $firstArgumentValue = $node->args[0]->value;
        if ($firstArgumentValue instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall) {
            return null;
        }
        foreach ($this->functionNameWithAssertMethods as $functionNameWithAssertMethod) {
            if (!$this->isName($firstArgumentValue, $functionNameWithAssertMethod->getFunctionName())) {
                continue;
            }
            $name = $this->getName($firstArgumentValue);
            if ($name === null) {
                return null;
            }
            $this->renameMethod($node, $functionNameWithAssertMethod);
            $this->moveFunctionArgumentsUp($node);
            return $node;
        }
        return null;
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    private function renameMethod(\_PhpScoperb75b35f52b74\PhpParser\Node $node, \_PhpScoperb75b35f52b74\Rector\PHPUnit\ValueObject\FunctionNameWithAssertMethods $functionNameWithAssertMethods) : void
    {
        /** @var Identifier $identifierNode */
        $identifierNode = $node->name;
        $oldMethodName = $identifierNode->toString();
        if ($functionNameWithAssertMethods->getAssetMethodName() && \in_array($oldMethodName, ['assertTrue', 'assertNotFalse'], \true)) {
            $node->name = new \_PhpScoperb75b35f52b74\PhpParser\Node\Identifier($functionNameWithAssertMethods->getAssetMethodName());
        }
        if ($functionNameWithAssertMethods->getNotAssertMethodName() === '') {
            return;
        }
        if (!\in_array($oldMethodName, ['assertFalse', 'assertNotTrue'], \true)) {
            return;
        }
        $node->name = new \_PhpScoperb75b35f52b74\PhpParser\Node\Identifier($functionNameWithAssertMethods->getNotAssertMethodName());
    }
    /**
     * Before:
     * - $this->assertTrue(array_key_exists('...', ['...']), 'second argument');
     *
     * After:
     * - $this->assertArrayHasKey('...', ['...'], 'second argument');
     *
     * @param MethodCall|StaticCall $node
     */
    private function moveFunctionArgumentsUp(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : void
    {
        $funcCallOrEmptyNode = $node->args[0]->value;
        if ($funcCallOrEmptyNode instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall) {
            $funcCallOrEmptyNodeName = $this->getName($funcCallOrEmptyNode);
            if ($funcCallOrEmptyNodeName === null) {
                return;
            }
            $funcCallOrEmptyNodeArgs = $funcCallOrEmptyNode->args;
            $oldArguments = $node->args;
            unset($oldArguments[0]);
            $node->args = $this->buildNewArguments($funcCallOrEmptyNodeName, $funcCallOrEmptyNodeArgs, $oldArguments);
        }
        if ($funcCallOrEmptyNode instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Empty_) {
            $node->args[0] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Arg($funcCallOrEmptyNode->expr);
        }
    }
    /**
     * @param Arg[] $funcCallOrEmptyNodeArgs
     * @param Arg[] $oldArguments
     * @return mixed[]
     */
    private function buildNewArguments(string $funcCallOrEmptyNodeName, array $funcCallOrEmptyNodeArgs, array $oldArguments) : array
    {
        if (\in_array($funcCallOrEmptyNodeName, ['in_array', 'array_search'], \true) && \count($funcCallOrEmptyNodeArgs) === 3) {
            unset($funcCallOrEmptyNodeArgs[2]);
            return $this->appendArgs($funcCallOrEmptyNodeArgs, $oldArguments);
        }
        if ($funcCallOrEmptyNodeName === 'is_a') {
            $newArgs = [$funcCallOrEmptyNodeArgs[1], $funcCallOrEmptyNodeArgs[0]];
            return $this->appendArgs($newArgs, $oldArguments);
        }
        return $this->appendArgs($funcCallOrEmptyNodeArgs, $oldArguments);
    }
}
