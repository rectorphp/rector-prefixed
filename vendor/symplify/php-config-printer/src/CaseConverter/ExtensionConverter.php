<?php

declare (strict_types=1);
namespace RectorPrefix20210317\Symplify\PhpConfigPrinter\CaseConverter;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\MethodName;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\VariableName;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlKey;
final class ExtensionConverter implements \RectorPrefix20210317\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface
{
    /**
     * @var ArgsNodeFactory
     */
    private $argsNodeFactory;
    /**
     * @var string
     */
    private $rootKey;
    /**
     * @var YamlKey
     */
    private $yamlKey;
    /**
     * @param \Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory $argsNodeFactory
     * @param \Symplify\PhpConfigPrinter\ValueObject\YamlKey $yamlKey
     */
    public function __construct($argsNodeFactory, $yamlKey)
    {
        $this->argsNodeFactory = $argsNodeFactory;
        $this->yamlKey = $yamlKey;
    }
    public function convertToMethodCall($key, $values) : \PhpParser\Node\Stmt\Expression
    {
        $args = $this->argsNodeFactory->createFromValues([$this->rootKey, [$key => $values]]);
        $containerConfiguratorVariable = new \PhpParser\Node\Expr\Variable(\RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\VariableName::CONTAINER_CONFIGURATOR);
        $methodCall = new \PhpParser\Node\Expr\MethodCall($containerConfiguratorVariable, \RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\MethodName::EXTENSION, $args);
        return new \PhpParser\Node\Stmt\Expression($methodCall);
    }
    public function match(string $rootKey, $key, $values) : bool
    {
        $this->rootKey = $rootKey;
        return !\in_array($rootKey, $this->yamlKey->provideRootKeys(), \true);
    }
}
