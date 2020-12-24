<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\CaseConverter;

use _PhpScopere8e811afab72\Nette\Utils\Strings;
use _PhpScopere8e811afab72\PhpParser\Node\Arg;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\Concat;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Variable;
use _PhpScopere8e811afab72\PhpParser\Node\Scalar\String_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression;
use _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface;
use _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory;
use _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\NodeFactory\CommonNodeFactory;
use _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory;
use _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\MethodName;
use _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\VariableName;
use _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\YamlKey;
use _PhpScopere8e811afab72\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
/**
 * Handles this part:
 *
 * services:
 *     Some: Other <---
 */
final class AliasCaseConverter implements \_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface
{
    /**
     * @see https://regex101.com/r/BwXkfO/2/
     * @var string
     */
    private const ARGUMENT_NAME_REGEX = '#\\$(?<argument_name>\\w+)#';
    /**
     * @see https://regex101.com/r/DDuuVM/1
     * @var string
     */
    private const NAMED_ALIAS_REGEX = '#\\w+\\s+\\$\\w+#';
    /**
     * @var CommonNodeFactory
     */
    private $commonNodeFactory;
    /**
     * @var ArgsNodeFactory
     */
    private $argsNodeFactory;
    /**
     * @var ServiceOptionNodeFactory
     */
    private $serviceOptionNodeFactory;
    public function __construct(\_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\NodeFactory\CommonNodeFactory $commonNodeFactory, \_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory $argsNodeFactory, \_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory $serviceOptionNodeFactory)
    {
        $this->commonNodeFactory = $commonNodeFactory;
        $this->argsNodeFactory = $argsNodeFactory;
        $this->serviceOptionNodeFactory = $serviceOptionNodeFactory;
    }
    public function convertToMethodCall($key, $values) : \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression
    {
        if (!\is_string($key)) {
            throw new \_PhpScopere8e811afab72\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
        }
        $servicesVariable = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable(\_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\VariableName::SERVICES);
        if (\class_exists($key) || \interface_exists($key)) {
            $classReference = $this->commonNodeFactory->createClassReference($key);
            $argValues = [];
            $argValues[] = $classReference;
            $argValues[] = $values[\_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\MethodName::ALIAS] ?? $values;
            $args = $this->argsNodeFactory->createFromValues($argValues, \true);
            $methodCall = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall($servicesVariable, \_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\MethodName::ALIAS, $args);
            return new \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression($methodCall);
        }
        // handles: "SomeClass $someVariable: ..."
        $fullClassName = \_PhpScopere8e811afab72\Nette\Utils\Strings::before($key, ' $');
        if ($fullClassName !== null) {
            $methodCall = $this->createAliasNode($key, $fullClassName, $values);
            return new \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression($methodCall);
        }
        $methodCall = null;
        if (isset($values[\_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\MethodName::ALIAS])) {
            $className = $values[\_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\MethodName::ALIAS];
            $classReference = $this->commonNodeFactory->createClassReference($className);
            $args = $this->argsNodeFactory->createFromValues([$key, $classReference]);
            $methodCall = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall($servicesVariable, \_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\MethodName::ALIAS, $args);
            unset($values[\_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\MethodName::ALIAS]);
        }
        /** @var string|mixed[] $values */
        if (\is_string($values) && $values[0] === '@') {
            $args = $this->argsNodeFactory->createFromValues([$values], \true);
            $methodCall = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall($servicesVariable, \_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\MethodName::ALIAS, $args);
        } elseif (\is_array($values)) {
            if ($methodCall === null) {
                throw new \_PhpScopere8e811afab72\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
            }
            /** @var MethodCall $methodCall */
            $methodCall = $this->serviceOptionNodeFactory->convertServiceOptionsToNodes($values, $methodCall);
        }
        if ($methodCall === null) {
            throw new \_PhpScopere8e811afab72\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
        }
        return new \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression($methodCall);
    }
    public function match(string $rootKey, $key, $values) : bool
    {
        if ($rootKey !== \_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\YamlKey::SERVICES) {
            return \false;
        }
        if (isset($values[\_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\YamlKey::ALIAS])) {
            return \true;
        }
        if (\_PhpScopere8e811afab72\Nette\Utils\Strings::match($key, self::NAMED_ALIAS_REGEX)) {
            return \true;
        }
        return \is_string($values) && $values[0] === '@';
    }
    private function createAliasNode(string $key, string $fullClassName, $serviceValues) : \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall
    {
        $args = [];
        $classConstFetch = $this->commonNodeFactory->createClassReference($fullClassName);
        \_PhpScopere8e811afab72\Nette\Utils\Strings::match($key, self::ARGUMENT_NAME_REGEX);
        $argumentName = '$' . \_PhpScopere8e811afab72\Nette\Utils\Strings::after($key, '$');
        $concat = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\Concat($classConstFetch, new \_PhpScopere8e811afab72\PhpParser\Node\Scalar\String_(' ' . $argumentName));
        $args[] = new \_PhpScopere8e811afab72\PhpParser\Node\Arg($concat);
        $serviceName = \ltrim($serviceValues, '@');
        $args[] = new \_PhpScopere8e811afab72\PhpParser\Node\Arg(new \_PhpScopere8e811afab72\PhpParser\Node\Scalar\String_($serviceName));
        return new \_PhpScopere8e811afab72\PhpParser\Node\Expr\MethodCall(new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable(\_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\VariableName::SERVICES), \_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\ValueObject\MethodName::ALIAS, $args);
    }
}
