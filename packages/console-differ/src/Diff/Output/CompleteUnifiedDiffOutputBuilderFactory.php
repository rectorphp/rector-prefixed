<?php

declare (strict_types=1);
namespace Rector\ConsoleDiffer\Diff\Output;

use RectorPrefix20201230\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use RectorPrefix20201230\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
/**
 * Creates @see UnifiedDiffOutputBuilder with "$contextLines = 1000;"
 */
final class CompleteUnifiedDiffOutputBuilderFactory
{
    /**
     * @var PrivatesAccessor
     */
    private $privatesAccessor;
    public function __construct()
    {
        $this->privatesAccessor = new \RectorPrefix20201230\Symplify\PackageBuilder\Reflection\PrivatesAccessor();
    }
    /**
     * @api
     */
    public function create() : \RectorPrefix20201230\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder
    {
        $unifiedDiffOutputBuilder = new \RectorPrefix20201230\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder('');
        $this->privatesAccessor->setPrivateProperty($unifiedDiffOutputBuilder, 'contextLines', 1000);
        return $unifiedDiffOutputBuilder;
    }
}
