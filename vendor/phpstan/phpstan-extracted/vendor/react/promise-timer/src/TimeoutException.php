<?php

namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Promise\Timer;

use RuntimeException;
class TimeoutException extends \RuntimeException
{
    private $timeout;
    public function __construct($timeout, $message = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->timeout = $timeout;
    }
    public function getTimeout()
    {
        return $this->timeout;
    }
}
