<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Nette\DI\Extensions;

use _PhpScoperbd5d0c5f7638\Nette;
/**
 * PHP directives definition.
 */
final class PhpExtension extends \_PhpScoperbd5d0c5f7638\Nette\DI\CompilerExtension
{
    public function getConfigSchema() : \_PhpScoperbd5d0c5f7638\Nette\Schema\Schema
    {
        return \_PhpScoperbd5d0c5f7638\Nette\Schema\Expect::arrayOf('scalar');
    }
    public function loadConfiguration()
    {
        foreach ($this->getConfig() as $name => $value) {
            if ($value === null) {
                continue;
            } elseif ($name === 'include_path') {
                $this->initialization->addBody('set_include_path(?);', [\str_replace(';', \PATH_SEPARATOR, $value)]);
            } elseif ($name === 'ignore_user_abort') {
                $this->initialization->addBody('ignore_user_abort(?);', [$value]);
            } elseif ($name === 'max_execution_time') {
                $this->initialization->addBody('set_time_limit(?);', [$value]);
            } elseif ($name === 'date.timezone') {
                $this->initialization->addBody('date_default_timezone_set(?);', [$value]);
            } elseif (\function_exists('ini_set')) {
                $this->initialization->addBody('ini_set(?, ?);', [$name, $value === \false ? '0' : (string) $value]);
            } elseif (\ini_get($name) !== (string) $value) {
                throw new \_PhpScoperbd5d0c5f7638\Nette\NotSupportedException('Required function ini_set() is disabled.');
            }
        }
    }
}
