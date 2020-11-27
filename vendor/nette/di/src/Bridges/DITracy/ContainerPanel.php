<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Nette\Bridges\DITracy;

use _PhpScoperbd5d0c5f7638\Nette;
use _PhpScoperbd5d0c5f7638\Nette\DI\Container;
use _PhpScoperbd5d0c5f7638\Tracy;
/**
 * Dependency injection container panel for Debugger Bar.
 */
class ContainerPanel implements \_PhpScoperbd5d0c5f7638\Tracy\IBarPanel
{
    use Nette\SmartObject;
    /** @var float|null */
    public static $compilationTime;
    /** @var Nette\DI\Container */
    private $container;
    /** @var float|null */
    private $elapsedTime;
    public function __construct(\_PhpScoperbd5d0c5f7638\Nette\DI\Container $container)
    {
        $this->container = $container;
        $this->elapsedTime = self::$compilationTime ? \microtime(\true) - self::$compilationTime : null;
    }
    /**
     * Renders tab.
     */
    public function getTab() : string
    {
        return \_PhpScoperbd5d0c5f7638\Nette\Utils\Helpers::capture(function () {
            $elapsedTime = $this->elapsedTime;
            require __DIR__ . '/templates/ContainerPanel.tab.phtml';
        });
    }
    /**
     * Renders panel.
     */
    public function getPanel() : string
    {
        $rc = new \ReflectionClass($this->container);
        $tags = [];
        $types = [];
        foreach ($rc->getMethods() as $method) {
            if (\preg_match('#^createService(.+)#', $method->name, $m) && $method->getReturnType()) {
                $types[\lcfirst(\str_replace('__', '.', $m[1]))] = $method->getReturnType()->getName();
            }
        }
        $types = $this->getContainerProperty('types') + $types;
        \ksort($types);
        foreach ($this->getContainerProperty('tags') as $tag => $tmp) {
            foreach ($tmp as $service => $val) {
                $tags[$service][$tag] = $val;
            }
        }
        return \_PhpScoperbd5d0c5f7638\Nette\Utils\Helpers::capture(function () use($tags, $types, $rc) {
            $container = $this->container;
            $file = $rc->getFileName();
            $instances = $this->getContainerProperty('instances');
            $wiring = $this->getContainerProperty('wiring');
            require __DIR__ . '/templates/ContainerPanel.panel.phtml';
        });
    }
    private function getContainerProperty(string $name)
    {
        $prop = (new \ReflectionClass(\_PhpScoperbd5d0c5f7638\Nette\DI\Container::class))->getProperty($name);
        $prop->setAccessible(\true);
        return $prop->getValue($this->container);
    }
}