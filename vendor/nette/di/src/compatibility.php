<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Nette\DI\Config;

if (\false) {
    /** @deprecated use Nette\DI\Config\Adapter */
    interface IAdapter
    {
    }
} elseif (!\interface_exists(\_PhpScoperbd5d0c5f7638\Nette\DI\Config\IAdapter::class)) {
    \class_alias(\_PhpScoperbd5d0c5f7638\Nette\DI\Config\Adapter::class, \_PhpScoperbd5d0c5f7638\Nette\DI\Config\IAdapter::class);
}
namespace _PhpScoperbd5d0c5f7638\Nette\DI;

if (\false) {
    /** @deprecated use Nette\DI\Definitions\ServiceDefinition */
    class ServiceDefinition
    {
    }
} elseif (!\class_exists(\_PhpScoperbd5d0c5f7638\Nette\DI\ServiceDefinition::class)) {
    \class_alias(\_PhpScoperbd5d0c5f7638\Nette\DI\Definitions\ServiceDefinition::class, \_PhpScoperbd5d0c5f7638\Nette\DI\ServiceDefinition::class);
}
if (\false) {
    /** @deprecated use Nette\DI\Definitions\Statement */
    class Statement
    {
    }
} elseif (!\class_exists(\_PhpScoperbd5d0c5f7638\Nette\DI\Statement::class)) {
    \class_alias(\_PhpScoperbd5d0c5f7638\Nette\DI\Definitions\Statement::class, \_PhpScoperbd5d0c5f7638\Nette\DI\Statement::class);
}