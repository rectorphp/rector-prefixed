<?php

declare (strict_types=1);
namespace RectorPrefix20210317\Symplify\PhpConfigPrinter\CaseConverter;

use PhpParser\Node\Stmt\Expression;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\NodeFactory\Service\ServicesPhpNodeFactory;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlKey;
final class ResourceCaseConverter implements \RectorPrefix20210317\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface
{
    /**
     * @var ServicesPhpNodeFactory
     */
    private $servicesPhpNodeFactory;
    /**
     * @param \Symplify\PhpConfigPrinter\NodeFactory\Service\ServicesPhpNodeFactory $servicesPhpNodeFactory
     */
    public function __construct($servicesPhpNodeFactory)
    {
        $this->servicesPhpNodeFactory = $servicesPhpNodeFactory;
    }
    public function convertToMethodCall($key, $values) : \PhpParser\Node\Stmt\Expression
    {
        // Due to the yaml behavior that does not allow the declaration of several identical key names.
        if (isset($values['namespace'])) {
            $key = $values['namespace'];
            unset($values['namespace']);
        }
        return $this->servicesPhpNodeFactory->createResource($key, $values);
    }
    public function match(string $rootKey, $key, $values) : bool
    {
        return isset($values[\RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\YamlKey::RESOURCE]);
    }
}
