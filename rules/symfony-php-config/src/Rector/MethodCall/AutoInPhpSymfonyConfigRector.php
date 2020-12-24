<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\SymfonyPhpConfig\Rector\MethodCall;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\Defluent\NodeAnalyzer\FluentChainMethodCallNodeAnalyzer;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Rector\SymfonyPhpConfig\NodeAnalyzer\SymfonyPhpConfigClosureAnalyzer;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see phpstan rule https://github.com/symplify/coding-standard/blob/master/docs/phpstan_rules.md#check-required-autowire-autoconfigure-and-public-are-used-in-config-service-rule
 * @see \Rector\SymfonyPhpConfig\Tests\Rector\MethodCall\AutoInPhpSymfonyConfigRector\AutoInPhpSymfonyConfigRectorTest
 */
final class AutoInPhpSymfonyConfigRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string[]
     */
    private const REQUIRED_METHOD_NAMES = ['public', 'autowire', 'autoconfigure'];
    /**
     * @var SymfonyPhpConfigClosureAnalyzer
     */
    private $symfonyPhpConfigClosureAnalyzer;
    /**
     * @var FluentChainMethodCallNodeAnalyzer
     */
    private $fluentChainMethodCallNodeAnalyzer;
    public function __construct(\_PhpScopere8e811afab72\Rector\SymfonyPhpConfig\NodeAnalyzer\SymfonyPhpConfigClosureAnalyzer $symfonyPhpConfigClosureAnalyzer, \_PhpScopere8e811afab72\Rector\Defluent\NodeAnalyzer\FluentChainMethodCallNodeAnalyzer $fluentChainMethodCallNodeAnalyzer)
    {
        $this->symfonyPhpConfigClosureAnalyzer = $symfonyPhpConfigClosureAnalyzer;
        $this->fluentChainMethodCallNodeAnalyzer = $fluentChainMethodCallNodeAnalyzer;
    }
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Make sure there is public(), autowire(), autoconfigure() calls on defaults() in Symfony configs', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire();
};
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->public()
        ->autoconfigure();
};
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
        if ($this->shouldSkipMethodCall($node)) {
            return null;
        }
        $missingMethodNames = $this->resolveMissingMethodNames($node);
        if ($missingMethodNames === []) {
            return null;
        }
        $node->setAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::IS_FRESH_NODE, \true);
        $methodCall = clone $node;
        foreach ($missingMethodNames as $missingMethodName) {
            $methodCall = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall($methodCall, $missingMethodName);
        }
        return $methodCall;
    }
    private function shouldSkipMethodCall(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall) : bool
    {
        $isFreshNode = $methodCall->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::IS_FRESH_NODE);
        if ($isFreshNode) {
            return \true;
        }
        $closure = $methodCall->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::CLOSURE_NODE);
        if ($closure === null) {
            return \true;
        }
        if (!$this->symfonyPhpConfigClosureAnalyzer->isPhpConfigClosure($closure)) {
            return \true;
        }
        if (!$this->fluentChainMethodCallNodeAnalyzer->isLastChainMethodCall($methodCall)) {
            return \true;
        }
        $rootMethodCall = $this->fluentChainMethodCallNodeAnalyzer->resolveRootMethodCall($methodCall);
        if ($rootMethodCall === null) {
            return \true;
        }
        return !$this->isName($rootMethodCall->name, 'defaults');
    }
    /**
     * @return string[]
     */
    private function resolveMissingMethodNames(\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall $methodCall) : array
    {
        $methodCallNames = $this->fluentChainMethodCallNodeAnalyzer->collectMethodCallNamesInChain($methodCall);
        return \array_diff(self::REQUIRED_METHOD_NAMES, $methodCallNames);
    }
}
