<?php

declare (strict_types=1);
namespace RectorPrefix20201228\Symplify\PhpConfigPrinter\CaseConverter;

use RectorPrefix20201228\Nette\Utils\Strings;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use RectorPrefix20201228\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface;
use RectorPrefix20201228\Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory;
use RectorPrefix20201228\Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory;
use RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\MethodName;
use RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\VariableName;
use RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\YamlKey;
/**
 * Handles this part:
 *
 * services:
 *     SomeNamespace\SomeClass: null <---
 */
final class ConfiguredServiceCaseConverter implements \RectorPrefix20201228\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface
{
    /**
     * @var ArgsNodeFactory
     */
    private $argsNodeFactory;
    /**
     * @var ServiceOptionNodeFactory
     */
    private $serviceOptionNodeFactory;
    public function __construct(\RectorPrefix20201228\Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory $argsNodeFactory, \RectorPrefix20201228\Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory $serviceOptionNodeFactory)
    {
        $this->argsNodeFactory = $argsNodeFactory;
        $this->serviceOptionNodeFactory = $serviceOptionNodeFactory;
    }
    public function convertToMethodCall($key, $values) : \PhpParser\Node\Stmt\Expression
    {
        $valuesForArgs = [$key];
        if (isset($values[\RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\YamlKey::CLASS_KEY])) {
            $valuesForArgs[] = $values[\RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\YamlKey::CLASS_KEY];
        }
        $args = $this->argsNodeFactory->createFromValues($valuesForArgs);
        $methodCall = new \PhpParser\Node\Expr\MethodCall(new \PhpParser\Node\Expr\Variable(\RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\VariableName::SERVICES), \RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\MethodName::SET, $args);
        $methodCall = $this->serviceOptionNodeFactory->convertServiceOptionsToNodes($values, $methodCall);
        return new \PhpParser\Node\Stmt\Expression($methodCall);
    }
    public function match(string $rootKey, $key, $values) : bool
    {
        if ($rootKey !== \RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\YamlKey::SERVICES) {
            return \false;
        }
        if ($key === \RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\YamlKey::_DEFAULTS) {
            return \false;
        }
        if ($key === \RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\YamlKey::_INSTANCEOF) {
            return \false;
        }
        if (isset($values[\RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\YamlKey::RESOURCE])) {
            return \false;
        }
        // handled by @see \Symplify\PhpConfigPrinter\CaseConverter\CaseConverter\AliasCaseConverter
        if ($this->isAlias($values)) {
            return \false;
        }
        if ($values === null) {
            return \false;
        }
        return $values !== [];
    }
    private function isAlias($values) : bool
    {
        if (isset($values[\RectorPrefix20201228\Symplify\PhpConfigPrinter\ValueObject\YamlKey::ALIAS])) {
            return \true;
        }
        return \is_string($values) && \RectorPrefix20201228\Nette\Utils\Strings::startsWith($values, '@');
    }
}
