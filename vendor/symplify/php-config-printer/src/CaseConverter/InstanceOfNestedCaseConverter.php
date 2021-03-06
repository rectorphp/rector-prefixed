<?php

declare (strict_types=1);
namespace RectorPrefix20210317\Symplify\PhpConfigPrinter\CaseConverter;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\NodeFactory\CommonNodeFactory;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\MethodName;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\VariableName;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlKey;
final class InstanceOfNestedCaseConverter
{
    /**
     * @var CommonNodeFactory
     */
    private $commonNodeFactory;
    /**
     * @var ServiceOptionNodeFactory
     */
    private $serviceOptionNodeFactory;
    public function __construct(\RectorPrefix20210317\Symplify\PhpConfigPrinter\NodeFactory\CommonNodeFactory $commonNodeFactory, \RectorPrefix20210317\Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory $serviceOptionNodeFactory)
    {
        $this->commonNodeFactory = $commonNodeFactory;
        $this->serviceOptionNodeFactory = $serviceOptionNodeFactory;
    }
    public function convertToMethodCall($key, $values) : \PhpParser\Node\Stmt\Expression
    {
        $classConstFetch = $this->commonNodeFactory->createClassReference($key);
        $servicesVariable = new \PhpParser\Node\Expr\Variable(\RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\VariableName::SERVICES);
        $args = [new \PhpParser\Node\Arg($classConstFetch)];
        $instanceofMethodCall = new \PhpParser\Node\Expr\MethodCall($servicesVariable, \RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\MethodName::INSTANCEOF, $args);
        $instanceofMethodCall = $this->serviceOptionNodeFactory->convertServiceOptionsToNodes($values, $instanceofMethodCall);
        return new \PhpParser\Node\Stmt\Expression($instanceofMethodCall);
    }
    public function isMatch(string $rootKey, $subKey) : bool
    {
        if ($rootKey !== \RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlKey::SERVICES) {
            return \false;
        }
        if (!\is_string($subKey)) {
            return \false;
        }
        return $subKey === \RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlKey::_INSTANCEOF;
    }
}
