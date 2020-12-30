<?php

declare (strict_types=1);
namespace RectorPrefix20201230\Symplify\PhpConfigPrinter\Naming;

use RectorPrefix20201230\Nette\Utils\Strings;
final class ClassNaming
{
    public function getShortName(string $class) : string
    {
        if (\RectorPrefix20201230\Nette\Utils\Strings::contains($class, '\\')) {
            return (string) \RectorPrefix20201230\Nette\Utils\Strings::after($class, '\\', -1);
        }
        return $class;
    }
}
