<?php

namespace _PhpScoper88fe6e0ad041\RingCentral\Tests\Psr7;

\error_reporting(\E_ALL);
require __DIR__ . '/../vendor/autoload.php';
class HasToString
{
    public function __toString()
    {
        return 'foo';
    }
}
