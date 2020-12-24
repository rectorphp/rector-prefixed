<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\DI\Config\Adapters;

use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette;
/**
 * Reading and generating PHP files.
 */
final class PhpAdapter implements \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\DI\Config\Adapter
{
    use Nette\SmartObject;
    /**
     * Reads configuration from PHP file.
     */
    public function load(string $file) : array
    {
        return require $file;
    }
    /**
     * Generates configuration in PHP format.
     */
    public function dump(array $data) : string
    {
        return "<?php // generated by Nette \nreturn " . \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\PhpGenerator\Helpers::dump($data) . ';';
    }
}
