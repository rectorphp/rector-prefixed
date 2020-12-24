<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\DI\Config;

use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\Utils\Validators;
/**
 * Configuration file loader.
 */
class Loader
{
    use Nette\SmartObject;
    private const INCLUDES_KEY = 'includes';
    private $adapters = ['php' => \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\DI\Config\Adapters\PhpAdapter::class, 'neon' => \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\DI\Config\Adapters\NeonAdapter::class];
    private $dependencies = [];
    private $loadedFiles = [];
    private $parameters = [];
    /**
     * Reads configuration from file.
     */
    public function load(string $file, ?bool $merge = \true) : array
    {
        if (!\is_file($file) || !\is_readable($file)) {
            throw new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\FileNotFoundException("File '{$file}' is missing or is not readable.");
        }
        if (isset($this->loadedFiles[$file])) {
            throw new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\InvalidStateException("Recursive included file '{$file}'");
        }
        $this->loadedFiles[$file] = \true;
        $this->dependencies[] = $file;
        $data = $this->getAdapter($file)->load($file);
        $res = [];
        if (isset($data[self::INCLUDES_KEY])) {
            \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\Utils\Validators::assert($data[self::INCLUDES_KEY], 'list', "section 'includes' in file '{$file}'");
            $includes = \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\DI\Helpers::expand($data[self::INCLUDES_KEY], $this->parameters);
            foreach ($includes as $include) {
                $include = $this->expandIncludedFile($include, $file);
                $res = \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\Schema\Helpers::merge($this->load($include, $merge), $res);
            }
        }
        unset($data[self::INCLUDES_KEY], $this->loadedFiles[$file]);
        if ($merge === \false) {
            $res[] = $data;
        } else {
            $res = \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\Schema\Helpers::merge($data, $res);
        }
        return $res;
    }
    /**
     * Save configuration to file.
     */
    public function save(array $data, string $file) : void
    {
        if (\file_put_contents($file, $this->getAdapter($file)->dump($data)) === \false) {
            throw new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\IOException("Cannot write file '{$file}'.");
        }
    }
    /**
     * Returns configuration files.
     */
    public function getDependencies() : array
    {
        return \array_unique($this->dependencies);
    }
    /**
     * Expands included file name.
     */
    public function expandIncludedFile(string $includedFile, string $mainFile) : string
    {
        return \preg_match('#([a-z]+:)?[/\\\\]#Ai', $includedFile) ? $includedFile : \dirname($mainFile) . '/' . $includedFile;
    }
    /**
     * Registers adapter for given file extension.
     * @param  string|Adapter  $adapter
     * @return static
     */
    public function addAdapter(string $extension, $adapter)
    {
        $this->adapters[\strtolower($extension)] = $adapter;
        return $this;
    }
    private function getAdapter(string $file) : \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\DI\Config\Adapter
    {
        $extension = \strtolower(\pathinfo($file, \PATHINFO_EXTENSION));
        if (!isset($this->adapters[$extension])) {
            throw new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Nette\InvalidArgumentException("Unknown file extension '{$file}'.");
        }
        return \is_object($this->adapters[$extension]) ? $this->adapters[$extension] : new $this->adapters[$extension]();
    }
    /** @return static */
    public function setParameters(array $params)
    {
        $this->parameters = $params;
        return $this;
    }
}
