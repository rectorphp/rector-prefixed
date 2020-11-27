<?php

// lint >= 7.4
namespace _PhpScoperbd5d0c5f7638\Bug3276;

use function PHPStan\Analyser\assertType;
class Foo
{
    /**
     * @param array{name?:string} $settings
     */
    public function doFoo(array $settings) : void
    {
        $settings['name'] ??= 'unknown';
        \PHPStan\Analyser\assertType('array(\'name\' => string)', $settings);
    }
    /**
     * @param array{name?:string} $settings
     */
    public function doBar(array $settings) : void
    {
        $settings['name'] = 'unknown';
        \PHPStan\Analyser\assertType('array(\'name\' => \'unknown\')', $settings);
    }
}