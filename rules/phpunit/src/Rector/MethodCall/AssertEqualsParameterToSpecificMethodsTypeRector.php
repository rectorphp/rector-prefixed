<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\PHPUnit\Rector\MethodCall;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractPHPUnitRector;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/sebastianbergmann/phpunit/blob/master/ChangeLog-8.0.md
 * @see https://github.com/sebastianbergmann/phpunit/commit/90e9e0379584bdf34220322e202617cd56d8ba65
 * @see https://github.com/sebastianbergmann/phpunit/commit/a4b60a5c625ff98a52bb3222301d223be7367483
 * @see \Rector\PHPUnit\Tests\Rector\MethodCall\AssertEqualsParameterToSpecificMethodsTypeRector\AssertEqualsParameterToSpecificMethodsTypeRectorTest
 */
final class AssertEqualsParameterToSpecificMethodsTypeRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractPHPUnitRector
{
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change assertEquals()/assertNotEquals() method parameters to new specific alternatives', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $value = 'value';
        $this->assertEquals('string', $value, 'message', 5.0);

        $this->assertEquals('string', $value, 'message', 0.0, 20);

        $this->assertEquals('string', $value, 'message', 0.0, 10, true);

        $this->assertEquals('string', $value, 'message', 0.0, 10, false, true);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $value = 'value';
        $this->assertEqualsWithDelta('string', $value, 5.0, 'message');

        $this->assertEquals('string', $value, 'message', 0.0);

        $this->assertEqualsCanonicalizing('string', $value, 'message');

        $this->assertEqualsIgnoringCase('string', $value, 'message');
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
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall::class, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if (!$this->isPHPUnitMethodNames($node, ['assertEquals', 'assertNotEquals'])) {
            return null;
        }
        // 1. refactor to "assertEqualsIgnoringCase()"
        $this->processAssertEqualsIgnoringCase($node);
        // 2. refactor to "assertEqualsCanonicalizing()"
        $this->processAssertEqualsCanonicalizing($node);
        // 3. remove $maxDepth
        if (isset($node->args[4])) {
            // add new node only in case of non-default value
            unset($node->args[4]);
        }
        // 4. refactor $delta to "assertEqualsWithDelta()"
        $this->processAssertEqualsWithDelta($node);
        return $node;
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    private function processAssertEqualsIgnoringCase(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : void
    {
        if (isset($node->args[6])) {
            if ($this->isTrue($node->args[6]->value)) {
                $newMethodCall = $this->createPHPUnitCallWithName($node, 'assertEqualsIgnoringCase');
                $newMethodCall->args[0] = $node->args[0];
                $newMethodCall->args[1] = $node->args[1];
                $newMethodCall->args[2] = $node->args[2];
                $this->addNodeAfterNode($newMethodCall, $node);
            }
            unset($node->args[6]);
        }
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    private function processAssertEqualsCanonicalizing(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : void
    {
        if (isset($node->args[5])) {
            // add new node only in case of non-default value
            if ($this->isTrue($node->args[5]->value)) {
                $newMethodCall = $this->createPHPUnitCallWithName($node, 'assertEqualsCanonicalizing');
                $newMethodCall->args[0] = $node->args[0];
                $newMethodCall->args[1] = $node->args[1];
                $newMethodCall->args[2] = $node->args[2];
                $this->addNodeAfterNode($newMethodCall, $node);
            }
            unset($node->args[5]);
        }
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    private function processAssertEqualsWithDelta(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : void
    {
        if (isset($node->args[3])) {
            // add new node only in case of non-default value
            if ($this->getValue($node->args[3]->value) !== 0.0) {
                $newMethodCall = $this->createPHPUnitCallWithName($node, 'assertEqualsWithDelta');
                $newMethodCall->args[0] = $node->args[0];
                $newMethodCall->args[1] = $node->args[1];
                $newMethodCall->args[2] = $node->args[3];
                $newMethodCall->args[3] = $node->args[2];
                $this->addNodeAfterNode($newMethodCall, $node);
            }
            unset($node->args[3]);
        }
    }
}
