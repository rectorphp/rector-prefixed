<?php

declare (strict_types=1);
namespace RectorPrefix20210317\Symplify\PhpConfigPrinter\Configuration;

use RectorPrefix20210317\Symplify\PhpConfigPrinter\Contract\SymfonyVersionFeatureGuardInterface;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\FunctionName;
use RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\SymfonyVersionFeature;
final class SymfonyFunctionNameProvider
{
    /**
     * @var SymfonyVersionFeatureGuardInterface
     */
    private $symfonyVersionFeatureGuard;
    public function __construct(\RectorPrefix20210317\Symplify\PhpConfigPrinter\Contract\SymfonyVersionFeatureGuardInterface $symfonyVersionFeatureGuard)
    {
        $this->symfonyVersionFeatureGuard = $symfonyVersionFeatureGuard;
    }
    public function provideRefOrService() : string
    {
        if ($this->symfonyVersionFeatureGuard->isAtLeastSymfonyVersion(\RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\SymfonyVersionFeature::REF_OVER_SERVICE)) {
            return \RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\FunctionName::SERVICE;
        }
        return \RectorPrefix20210317\Symplify\PhpConfigPrinter\ValueObject\FunctionName::REF;
    }
}
