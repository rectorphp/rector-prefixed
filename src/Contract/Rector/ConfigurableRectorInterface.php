<?php

declare (strict_types=1);
namespace Rector\Core\Contract\Rector;

use Symplify\RuleDocGenerator\Contract\ConfigurableRuleInterface;
interface ConfigurableRectorInterface extends \Symplify\RuleDocGenerator\Contract\ConfigurableRuleInterface
{
    /**
     * @param mixed[] $configuration
     */
    public function configure($configuration) : void;
}
