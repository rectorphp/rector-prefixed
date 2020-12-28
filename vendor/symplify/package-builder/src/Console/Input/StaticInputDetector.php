<?php

declare (strict_types=1);
namespace RectorPrefix20201228\Symplify\PackageBuilder\Console\Input;

use RectorPrefix20201228\Symfony\Component\Console\Input\ArgvInput;
class StaticInputDetector
{
    public static function isDebug() : bool
    {
        $argvInput = new \RectorPrefix20201228\Symfony\Component\Console\Input\ArgvInput();
        return $argvInput->hasParameterOption(['--debug', '-v', '-vv', '-vvv']);
    }
}
