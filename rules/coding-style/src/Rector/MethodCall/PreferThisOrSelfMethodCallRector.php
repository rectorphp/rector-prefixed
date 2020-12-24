<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\CodingStyle\Rector\MethodCall;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall;
use _PhpScoperb75b35f52b74\PHPUnit\Framework\TestCase;
use _PhpScoperb75b35f52b74\Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use _PhpScoperb75b35f52b74\Rector\Core\Exception\Rector\InvalidRectorConfigurationException;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use _PhpScoperb75b35f52b74\Webmozart\Assert\Assert;
/**
 * @see \Rector\CodingStyle\Tests\Rector\MethodCall\PreferThisOrSelfMethodCallRector\PreferThisOrSelfMethodCallRectorTest
 */
final class PreferThisOrSelfMethodCallRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector implements \_PhpScoperb75b35f52b74\Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const TYPE_TO_PREFERENCE = 'type_to_preference';
    /**
     * @api
     * @var string
     */
    public const PREFER_SELF = self::SELF;
    /**
     * @api
     * @var string
     */
    public const PREFER_THIS = 'this';
    /**
     * @var string[]
     */
    private const ALLOWED_OPTIONS = [self::PREFER_THIS, self::PREFER_SELF];
    /**
     * @var string
     */
    private const SELF = 'self';
    /**
     * @var array<string, string>
     */
    private $typeToPreference = [];
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes $this->... and static:: to self:: or vise versa for given types', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeClass extends \PHPUnit\Framework\TestCase
{
    public function run()
    {
        $this->assertEquals('a', 'a');
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass extends \PHPUnit\Framework\TestCase
{
    public function run()
    {
        self::assertEquals('a', 'a');
    }
}
CODE_SAMPLE
, [self::TYPE_TO_PREFERENCE => [\_PhpScoperb75b35f52b74\PHPUnit\Framework\TestCase::class => self::PREFER_SELF]])]);
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
        foreach ($this->typeToPreference as $type => $preference) {
            if (!$this->isMethodStaticCallOrClassMethodObjectType($node, $type)) {
                continue;
            }
            if ($preference === self::PREFER_SELF) {
                return $this->processToSelf($node);
            }
            return $this->processToThis($node);
        }
        return null;
    }
    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration) : void
    {
        $typeToPreference = $configuration[self::TYPE_TO_PREFERENCE] ?? [];
        \_PhpScoperb75b35f52b74\Webmozart\Assert\Assert::allString($typeToPreference);
        foreach ($typeToPreference as $preference) {
            $this->ensurePreferenceIsValid($preference);
        }
        $this->typeToPreference = $typeToPreference;
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    private function processToSelf(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall
    {
        if ($node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall && !$this->isNames($node->class, [self::SELF, 'static'])) {
            return null;
        }
        if ($node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall && !$this->isName($node->var, 'this')) {
            return null;
        }
        $name = $this->getName($node->name);
        if ($name === null) {
            return null;
        }
        return $this->createStaticCall(self::SELF, $name, $node->args);
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    private function processToThis(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall
    {
        if ($node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall) {
            return null;
        }
        if (!$this->isNames($node->class, [self::SELF, 'static'])) {
            return null;
        }
        $name = $this->getName($node->name);
        if ($name === null) {
            return null;
        }
        return $this->createMethodCall('this', $name, $node->args);
    }
    private function ensurePreferenceIsValid(string $preference) : void
    {
        if (\in_array($preference, self::ALLOWED_OPTIONS, \true)) {
            return;
        }
        throw new \_PhpScoperb75b35f52b74\Rector\Core\Exception\Rector\InvalidRectorConfigurationException(\sprintf('Preference configuration "%s" for "%s" is not valid. Use one of "%s"', $preference, self::class, \implode('", "', self::ALLOWED_OPTIONS)));
    }
}
