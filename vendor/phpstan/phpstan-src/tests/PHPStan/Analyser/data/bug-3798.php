<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Bug3798;

/** @param callable(int ...$params) : void $c */
function acceptsVariadicCallable(callable $c) : void
{
    $c();
    $c(1);
    $c(1, 2, 3);
}