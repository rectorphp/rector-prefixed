<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Generic\Rector\ClassMethod;

use _PhpScopere8e811afab72\PhpParser\BuilderHelpers;
use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Arg;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Variable;
use _PhpScopere8e811afab72\PhpParser\Node\Identifier;
use _PhpScopere8e811afab72\PhpParser\Node\Name;
use _PhpScopere8e811afab72\PhpParser\Node\Name\FullyQualified;
use _PhpScopere8e811afab72\PhpParser\Node\Param;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod;
use _PhpScopere8e811afab72\Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use _PhpScopere8e811afab72\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\Generic\ValueObject\ArgumentAdder;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use _PhpScopere8e811afab72\Webmozart\Assert\Assert;
/**
 * @see \Rector\Generic\Tests\Rector\ClassMethod\ArgumentAdderRector\ArgumentAdderRectorTest
 */
final class ArgumentAdderRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector implements \_PhpScopere8e811afab72\Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const ADDED_ARGUMENTS = 'added_arguments';
    /**
     * @var string
     */
    public const SCOPE_PARENT_CALL = 'parent_call';
    /**
     * @var string
     */
    public const SCOPE_METHOD_CALL = 'method_call';
    /**
     * @var string
     */
    public const SCOPE_CLASS_METHOD = 'class_method';
    /**
     * @var ArgumentAdder[]
     */
    private $addedArguments = [];
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        $exampleConfiguration = [self::ADDED_ARGUMENTS => [new \_PhpScopere8e811afab72\Rector\Generic\ValueObject\ArgumentAdder('SomeExampleClass', 'someMethod', 0, 'someArgument', 'true', 'SomeType')]];
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('This Rector adds new default arguments in calls of defined methods and class types.', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
$someObject = new SomeExampleClass;
$someObject->someMethod();
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$someObject = new SomeExampleClass;
$someObject->someMethod(true);
CODE_SAMPLE
, $exampleConfiguration), new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class MyCustomClass extends SomeExampleClass
{
    public function someMethod()
    {
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class MyCustomClass extends SomeExampleClass
{
    public function someMethod($value = true)
    {
    }
}
CODE_SAMPLE
, $exampleConfiguration)]);
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
        foreach ($this->addedArguments as $addedArgument) {
            if (!$this->isObjectTypeMatch($node, $addedArgument->getClass())) {
                continue;
            }
            if (!$this->isName($node->name, $addedArgument->getMethod())) {
                continue;
            }
            $this->processPositionWithDefaultValues($node, $addedArgument);
        }
        return $node;
    }
    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration) : void
    {
        $addedArguments = $configuration[self::ADDED_ARGUMENTS] ?? [];
        \_PhpScopere8e811afab72\Webmozart\Assert\Assert::allIsInstanceOf($addedArguments, \_PhpScopere8e811afab72\Rector\Generic\ValueObject\ArgumentAdder::class);
        $this->addedArguments = $addedArguments;
    }
    /**
     * @param MethodCall|StaticCall|ClassMethod $node
     */
    private function isObjectTypeMatch(\_PhpScopere8e811afab72\PhpParser\Node $node, string $type) : bool
    {
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall) {
            return $this->isObjectType($node->var, $type);
        }
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall) {
            return $this->isObjectType($node->class, $type);
        }
        // ClassMethod
        /** @var Class_|null $classLike */
        $classLike = $node->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        // anonymous class
        if ($classLike === null) {
            return \false;
        }
        return $this->isObjectType($classLike, $type);
    }
    /**
     * @param ClassMethod|MethodCall|StaticCall $node
     */
    private function processPositionWithDefaultValues(\_PhpScopere8e811afab72\PhpParser\Node $node, \_PhpScopere8e811afab72\Rector\Generic\ValueObject\ArgumentAdder $argumentAdder) : void
    {
        if ($this->shouldSkipParameter($node, $argumentAdder)) {
            return;
        }
        $defaultValue = $argumentAdder->getArgumentDefaultValue();
        $argumentType = $argumentAdder->getArgumentType();
        $position = $argumentAdder->getPosition();
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod) {
            $this->addClassMethodParam($node, $argumentAdder, $defaultValue, $argumentType, $position);
        } elseif ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall) {
            $this->processStaticCall($node, $position, $argumentAdder);
        } else {
            $arg = new \_PhpScopere8e811afab72\PhpParser\Node\Arg(\_PhpScopere8e811afab72\PhpParser\BuilderHelpers::normalizeValue($defaultValue));
            if (isset($node->args[$position])) {
                return;
            }
            $node->args[$position] = $arg;
        }
    }
    /**
     * @param ClassMethod|MethodCall|StaticCall $node
     */
    private function shouldSkipParameter(\_PhpScopere8e811afab72\PhpParser\Node $node, \_PhpScopere8e811afab72\Rector\Generic\ValueObject\ArgumentAdder $argumentAdder) : bool
    {
        $position = $argumentAdder->getPosition();
        $argumentName = $argumentAdder->getArgumentName();
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod) {
            // already added?
            if (!isset($node->params[$position])) {
                return \false;
            }
            return $this->isName($node->params[$position], $argumentName);
        }
        // already added?
        if (isset($node->args[$position]) && $this->isName($node->args[$position], $argumentName)) {
            return \true;
        }
        // is correct scope?
        return !$this->isInCorrectScope($node, $argumentAdder);
    }
    /**
     * @param mixed $defaultValue
     */
    private function addClassMethodParam(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod $classMethod, \_PhpScopere8e811afab72\Rector\Generic\ValueObject\ArgumentAdder $argumentAdder, $defaultValue, ?string $type, int $position) : void
    {
        $argumentName = $argumentAdder->getArgumentName();
        if ($argumentName === null) {
            throw new \_PhpScopere8e811afab72\Rector\Core\Exception\ShouldNotHappenException();
        }
        $param = new \_PhpScopere8e811afab72\PhpParser\Node\Param(new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable($argumentName), \_PhpScopere8e811afab72\PhpParser\BuilderHelpers::normalizeValue($defaultValue));
        if ($type) {
            $param->type = \ctype_upper($type[0]) ? new \_PhpScopere8e811afab72\PhpParser\Node\Name\FullyQualified($type) : new \_PhpScopere8e811afab72\PhpParser\Node\Identifier($type);
        }
        $classMethod->params[$position] = $param;
    }
    private function processStaticCall(\_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall $staticCall, int $position, \_PhpScopere8e811afab72\Rector\Generic\ValueObject\ArgumentAdder $argumentAdder) : void
    {
        $argumentName = $argumentAdder->getArgumentName();
        if ($argumentName === null) {
            throw new \_PhpScopere8e811afab72\Rector\Core\Exception\ShouldNotHappenException();
        }
        if (!$staticCall->class instanceof \_PhpScopere8e811afab72\PhpParser\Node\Name) {
            return;
        }
        if (!$this->isName($staticCall->class, 'parent')) {
            return;
        }
        $staticCall->args[$position] = new \_PhpScopere8e811afab72\PhpParser\Node\Arg(new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable($argumentName));
    }
    /**
     * @param ClassMethod|MethodCall|StaticCall $node
     */
    private function isInCorrectScope(\_PhpScopere8e811afab72\PhpParser\Node $node, \_PhpScopere8e811afab72\Rector\Generic\ValueObject\ArgumentAdder $argumentAdder) : bool
    {
        if ($argumentAdder->getScope() === null) {
            return \true;
        }
        $scope = $argumentAdder->getScope();
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod) {
            return $scope === self::SCOPE_CLASS_METHOD;
        }
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall) {
            if (!$node->class instanceof \_PhpScopere8e811afab72\PhpParser\Node\Name) {
                return \false;
            }
            if ($this->isName($node->class, 'parent')) {
                return $scope === self::SCOPE_PARENT_CALL;
            }
            return $scope === self::SCOPE_METHOD_CALL;
        }
        // MethodCall
        return $scope === self::SCOPE_METHOD_CALL;
    }
}
