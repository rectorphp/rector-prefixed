<?php

namespace _PhpScoperbd5d0c5f7638\Bug3994;

class HelloWorld
{
    public function split(string $str) : void
    {
        $parts = \explode(".", $str);
        \assert(\count($parts) === 4);
    }
}
