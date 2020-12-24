<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Renaming\Rector\MethodCall;

use _PhpScopere8e811afab72\PhpParser\BuilderHelpers;
use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayDimFetch;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall;
use _PhpScopere8e811afab72\PhpParser\Node\Identifier;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassLike;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod;
use _PhpScopere8e811afab72\Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Rector\Renaming\Contract\MethodCallRenameInterface;
use _PhpScopere8e811afab72\Rector\Renaming\ValueObject\MethodCallRename;
use _PhpScopere8e811afab72\Rector\Renaming\ValueObject\MethodCallRenameWithArrayKey;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use _PhpScopere8e811afab72\Webmozart\Assert\Assert;
/**
 * @see \Rector\Renaming\Tests\Rector\MethodCall\RenameMethodRector\RenameMethodRectorTest
 */
final class RenameMethodRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector implements \_PhpScopere8e811afab72\Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const METHOD_CALL_RENAMES = 'method_call_renames';
    /**
     * @var MethodCallRenameInterface[]
     */
    private $methodCallRenames = [];
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns method names to new ones.', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
$someObject = new SomeExampleClass;
$someObject->oldMethod();
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$someObject = new SomeExampleClass;
$someObject->newMethod();
CODE_SAMPLE
, [self::METHOD_CALL_RENAMES => [new \_PhpScopere8e811afab72\Rector\Renaming\ValueObject\MethodCallRename('SomeExampleClass', 'oldMethod', 'newMethod')]])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall::class, \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param MethodCall|StaticCall|ClassMethod $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        foreach ($this->methodCallRenames as $methodCallRename) {
            if (!$this->isMethodStaticCallOrClassMethodObjectType($node, $methodCallRename->getOldClass())) {
                continue;
            }
            if (!$this->isName($node->name, $methodCallRename->getOldMethod())) {
                continue;
            }
            if ($this->skipClassMethod($node, $methodCallRename)) {
                continue;
            }
            $node->name = new \_PhpScopere8e811afab72\PhpParser\Node\Identifier($methodCallRename->getNewMethod());
            if ($methodCallRename instanceof \_PhpScopere8e811afab72\Rector\Renaming\ValueObject\MethodCallRenameWithArrayKey && !$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod) {
                return new \_PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayDimFetch($node, \_PhpScopere8e811afab72\PhpParser\BuilderHelpers::normalizeValue($methodCallRename->getArrayKey()));
            }
            return $node;
        }
        return null;
    }
    public function configure(array $configuration) : void
    {
        $methodCallRenames = $configuration[self::METHOD_CALL_RENAMES] ?? [];
        \_PhpScopere8e811afab72\Webmozart\Assert\Assert::allIsInstanceOf($methodCallRenames, \_PhpScopere8e811afab72\Rector\Renaming\Contract\MethodCallRenameInterface::class);
        $this->methodCallRenames = $methodCallRenames;
    }
    /**
     * @param MethodCall|StaticCall|ClassMethod $node
     */
    private function skipClassMethod(\_PhpScopere8e811afab72\PhpParser\Node $node, \_PhpScopere8e811afab72\Rector\Renaming\Contract\MethodCallRenameInterface $methodCallRename) : bool
    {
        if (!$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod) {
            return \false;
        }
        if ($this->shouldSkipForAlreadyExistingClassMethod($node, $methodCallRename)) {
            return \true;
        }
        return $this->shouldSkipForExactClassMethodForClassMethod($node, $methodCallRename->getOldClass());
    }
    private function shouldSkipForAlreadyExistingClassMethod(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod $classMethod, \_PhpScopere8e811afab72\Rector\Renaming\Contract\MethodCallRenameInterface $methodCallRename) : bool
    {
        if (!$classMethod instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod) {
            return \false;
        }
        /** @var ClassLike|null $classLike */
        $classLike = $classMethod->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            return \false;
        }
        return (bool) $classLike->getMethod($methodCallRename->getNewMethod());
    }
    private function shouldSkipForExactClassMethodForClassMethod(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod $classMethod, string $type) : bool
    {
        return $classMethod->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME) === $type;
    }
}
