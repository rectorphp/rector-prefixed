<?php

declare (strict_types=1);
namespace RectorPrefix20210202\Symplify\PhpConfigPrinter\ServiceOptionAnalyzer;

use RectorPrefix20210202\Nette\Utils\Strings;
final class ServiceOptionAnalyzer
{
    public function hasNamedArguments(array $data) : bool
    {
        if ($data === []) {
            return \false;
        }
        foreach (\array_keys($data) as $key) {
            if (!\RectorPrefix20210202\Nette\Utils\Strings::startsWith((string) $key, '$')) {
                return \false;
            }
        }
        return \true;
    }
}
