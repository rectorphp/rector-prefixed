<?php

declare (strict_types=1);
namespace RectorPrefix20210317\Symplify\PhpConfigPrinter\ServiceOptionConverter;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\Contract\Converter\ServiceOptionsKeyYamlToPhpFactoryInterface;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\NodeFactory\CommonNodeFactory;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlKey;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlServiceKey;
final class BindAutowireAutoconfigureServiceOptionKeyYamlToPhpFactory implements \RectorPrefix20210317\Symplify\PhpConfigPrinter\Contract\Converter\ServiceOptionsKeyYamlToPhpFactoryInterface
{
    /**
     * @var CommonNodeFactory
     */
    private $commonNodeFactory;
    /**
     * @param \Symplify\PhpConfigPrinter\NodeFactory\CommonNodeFactory $commonNodeFactory
     */
    public function __construct($commonNodeFactory)
    {
        $this->commonNodeFactory = $commonNodeFactory;
    }
    public function decorateServiceMethodCall($key, $yaml, $values, \PhpParser\Node\Expr\MethodCall $methodCall) : \PhpParser\Node\Expr\MethodCall
    {
        $method = $key;
        if ($key === 'shared') {
            $method = 'share';
        }
        $methodCall = new \PhpParser\Node\Expr\MethodCall($methodCall, $method);
        if ($yaml === \false) {
            $methodCall->args[] = new \PhpParser\Node\Arg($this->commonNodeFactory->createFalse());
        }
        return $methodCall;
    }
    public function isMatch($key, $values) : bool
    {
        return \in_array($key, [\RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlServiceKey::BIND, \RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlKey::AUTOWIRE, \RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlKey::AUTOCONFIGURE], \true);
    }
}
