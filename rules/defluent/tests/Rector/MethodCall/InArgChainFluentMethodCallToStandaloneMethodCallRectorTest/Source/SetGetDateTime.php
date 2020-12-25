<?php

declare (strict_types=1);
namespace Rector\Defluent\Tests\Rector\MethodCall\InArgChainFluentMethodCallToStandaloneMethodCallRectorTest\Source;

use _PhpScoper50d83356d739\Nette\Utils\DateTime;
final class SetGetDateTime
{
    /**
     * @var DateTime|null
     */
    private $dateMin = null;
    public function setDateMin(?\_PhpScoper50d83356d739\Nette\Utils\DateTime $dateTime = null)
    {
        $this->dateMin = $dateTime;
    }
    public function getDateMin() : ?\_PhpScoper50d83356d739\Nette\Utils\DateTime
    {
        return $this->dateMin;
    }
}
