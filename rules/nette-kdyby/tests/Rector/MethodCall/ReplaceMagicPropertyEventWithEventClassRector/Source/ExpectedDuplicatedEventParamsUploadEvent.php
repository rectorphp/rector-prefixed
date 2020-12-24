<?php

namespace _PhpScoperb75b35f52b74\Rector\NetteKdyby\Tests\Rector\MethodCall\ReplaceMagicPropertyEventWithEventClassRector\Fixture\Event;

final class DuplicatedEventParamsUploadEvent extends \_PhpScoperb75b35f52b74\Symfony\Contracts\EventDispatcher\Event
{
    /**
     * @var mixed
     */
    private $userOwnerId;
    /**
     * @var mixed
     */
    private $userNameValue;
    /**
     * @var string
     */
    private $someUnderscore;
    public function __construct($userOwnerId, $userNameValue, string $someUnderscore)
    {
        $this->userOwnerId = $userOwnerId;
        $this->userNameValue = $userNameValue;
        $this->someUnderscore = $someUnderscore;
    }
    public function getUserOwnerId()
    {
        return $this->userOwnerId;
    }
    public function getUserNameValue()
    {
        return $this->userNameValue;
    }
    public function getSomeUnderscore() : string
    {
        return $this->someUnderscore;
    }
}
