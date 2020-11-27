<?php

declare (strict_types=1);
namespace Rector\Defluent\Tests\Rector\MethodCall\InArgChainFluentMethodCallToStandaloneMethodCallRectorTest\Source;

use _PhpScoperbd5d0c5f7638\Nette\Utils\DateTime;
final class SetGetDateTime
{
    /**
     * @var DateTime|null
     */
    private $dateMin = null;
    public function setDateMin(?\_PhpScoperbd5d0c5f7638\Nette\Utils\DateTime $dateTime = null)
    {
        $this->dateMin = $dateTime;
    }
    public function getDateMin() : ?\_PhpScoperbd5d0c5f7638\Nette\Utils\DateTime
    {
        return $this->dateMin;
    }
}
