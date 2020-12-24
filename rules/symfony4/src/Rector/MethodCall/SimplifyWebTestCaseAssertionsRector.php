<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Symfony4\Rector\MethodCall;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Arg;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\PropertyFetch;
use _PhpScopere8e811afab72\PhpParser\Node\Scalar\LNumber;
use _PhpScopere8e811afab72\PhpParser\Node\Scalar\String_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://symfony.com/blog/new-in-symfony-4-3-better-test-assertions
 * @see https://github.com/symfony/symfony/pull/30813/files
 * @see \Rector\Symfony4\Tests\Rector\MethodCall\SimplifyWebTestCaseAssertionsRector\SimplifyWebTestCaseAssertionsRectorTest
 */
final class SimplifyWebTestCaseAssertionsRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const ASSERT_SAME = 'assertSame';
    /**
     * @var MethodCall
     */
    private $getStatusCodeMethodCall;
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Simplify use of assertions in WebTestCase', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

class SomeClass extends TestCase
{
    public function test()
    {
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testUrl()
    {
        $this->assertSame(301, $client->getResponse()->getStatusCode());
        $this->assertSame('https://example.com', $client->getResponse()->headers->get('Location'));
    }

    public function testContains()
    {
        $this->assertContains('Hello World', $crawler->filter('h1')->text());
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

class SomeClass extends TestCase
{
    public function test()
    {
         $this->assertResponseIsSuccessful();
    }

    public function testUrl()
    {
        $this->assertResponseRedirects('https://example.com', 301);
    }

    public function testContains()
    {
        $this->assertSelectorTextContains('h1', 'Hello World');
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
        return [\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param MethodCall $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        $clientGetResponseMethodCall = $this->createMethodCall('client', 'getResponse');
        $this->getStatusCodeMethodCall = $this->createMethodCall($clientGetResponseMethodCall, 'getStatusCode');
        if (!$this->isInWebTestCase($node)) {
            return null;
        }
        // assertResponseIsSuccessful
        $args = [];
        $args[] = new \_PhpScopere8e811afab72\PhpParser\Node\Arg(new \_PhpScopere8e811afab72\PhpParser\Node\Scalar\LNumber(200));
        $args[] = new \_PhpScopere8e811afab72\PhpParser\Node\Arg($this->getStatusCodeMethodCall);
        $methodCall = $this->createLocalMethodCall(self::ASSERT_SAME, $args);
        if ($this->areNodesEqual($node, $methodCall)) {
            return $this->createLocalMethodCall('assertResponseIsSuccessful');
        }
        // assertResponseStatusCodeSame
        $newNode = $this->processAssertResponseStatusCodeSame($node);
        if ($newNode !== null) {
            return $newNode;
        }
        // assertSelectorTextContains
        $args = $this->matchAssertContainsCrawlerArg($node);
        if ($args !== null) {
            return $this->createLocalMethodCall('assertSelectorTextContains', $args);
        }
        return $this->processAssertResponseRedirects($node);
    }
    private function isInWebTestCase(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall) : bool
    {
        $classLike = $methodCall->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            return \false;
        }
        return $this->isObjectType($classLike, '_PhpScopere8e811afab72\\Symfony\\Bundle\\FrameworkBundle\\Test\\WebTestCase');
    }
    private function processAssertResponseStatusCodeSame(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall) : ?\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall
    {
        if (!$this->isName($methodCall->name, self::ASSERT_SAME)) {
            return null;
        }
        if (!$this->areNodesEqual($methodCall->args[1]->value, $this->getStatusCodeMethodCall)) {
            return null;
        }
        $statusCode = $this->getValue($methodCall->args[0]->value);
        // handled by another methods
        if (\in_array($statusCode, [200, 301], \true)) {
            return null;
        }
        return $this->createLocalMethodCall('assertResponseStatusCodeSame', [$methodCall->args[0]]);
    }
    /**
     * @return Arg[]|null
     */
    private function matchAssertContainsCrawlerArg(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall) : ?array
    {
        if (!$this->isName($methodCall->name, 'assertContains')) {
            return null;
        }
        $comparedNode = $methodCall->args[1]->value;
        if (!$comparedNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
            return null;
        }
        if (!$comparedNode->var instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
            return null;
        }
        if (!$this->isVariableName($comparedNode->var->var, 'crawler')) {
            return null;
        }
        if (!$this->isName($comparedNode->name, 'text')) {
            return null;
        }
        $args = [];
        $args[] = $comparedNode->var->args[0];
        $args[] = $methodCall->args[0];
        return $args;
    }
    private function processAssertResponseRedirects(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        /** @var Expression|null $previousStatement */
        $previousStatement = $methodCall->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::PREVIOUS_STATEMENT);
        if (!$previousStatement instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression) {
            return null;
        }
        $previousNode = $previousStatement->expr;
        if (!$previousNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
            return null;
        }
        $args = [];
        $args[] = new \_PhpScopere8e811afab72\PhpParser\Node\Arg(new \_PhpScopere8e811afab72\PhpParser\Node\Scalar\LNumber(301));
        $args[] = new \_PhpScopere8e811afab72\PhpParser\Node\Arg($this->getStatusCodeMethodCall);
        $match = $this->createLocalMethodCall(self::ASSERT_SAME, $args);
        if ($this->areNodesEqual($previousNode, $match)) {
            $getResponseMethodCall = $this->createMethodCall('client', 'getResponse');
            $propertyFetch = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\PropertyFetch($getResponseMethodCall, 'headers');
            $clientGetLocation = $this->createMethodCall($propertyFetch, 'get', [new \_PhpScopere8e811afab72\PhpParser\Node\Arg(new \_PhpScopere8e811afab72\PhpParser\Node\Scalar\String_('Location'))]);
            if (!isset($methodCall->args[1])) {
                return null;
            }
            if ($this->areNodesEqual($methodCall->args[1]->value, $clientGetLocation)) {
                $args = [];
                $args[] = $methodCall->args[0];
                $args[] = $previousNode->args[0];
                $this->removeNode($previousNode);
                return $this->createLocalMethodCall('assertResponseRedirects', $args);
            }
        }
        return null;
    }
}
