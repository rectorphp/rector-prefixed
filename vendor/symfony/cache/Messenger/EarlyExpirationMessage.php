<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace _PhpScoper17db12703726\Symfony\Component\Cache\Messenger;

use _PhpScoper17db12703726\Symfony\Component\Cache\Adapter\AdapterInterface;
use _PhpScoper17db12703726\Symfony\Component\Cache\CacheItem;
use _PhpScoper17db12703726\Symfony\Component\DependencyInjection\ReverseContainer;
/**
 * Conveys a cached value that needs to be computed.
 */
final class EarlyExpirationMessage
{
    private $item;
    private $pool;
    private $callback;
    public static function create(\_PhpScoper17db12703726\Symfony\Component\DependencyInjection\ReverseContainer $reverseContainer, callable $callback, \_PhpScoper17db12703726\Symfony\Component\Cache\CacheItem $item, \_PhpScoper17db12703726\Symfony\Component\Cache\Adapter\AdapterInterface $pool) : ?self
    {
        try {
            $item = clone $item;
            $item->set(null);
        } catch (\Exception $e) {
            return null;
        }
        $pool = $reverseContainer->getId($pool);
        if (\is_object($callback)) {
            if (null === ($id = $reverseContainer->getId($callback))) {
                return null;
            }
            $callback = '@' . $id;
        } elseif (!\is_array($callback)) {
            $callback = (string) $callback;
        } elseif (!\is_object($callback[0])) {
            $callback = [(string) $callback[0], (string) $callback[1]];
        } else {
            if (null === ($id = $reverseContainer->getId($callback[0]))) {
                return null;
            }
            $callback = ['@' . $id, (string) $callback[1]];
        }
        return new self($item, $pool, $callback);
    }
    public function getItem() : \_PhpScoper17db12703726\Symfony\Component\Cache\CacheItem
    {
        return $this->item;
    }
    public function getPool() : string
    {
        return $this->pool;
    }
    public function getCallback()
    {
        return $this->callback;
    }
    public function findPool(\_PhpScoper17db12703726\Symfony\Component\DependencyInjection\ReverseContainer $reverseContainer) : \_PhpScoper17db12703726\Symfony\Component\Cache\Adapter\AdapterInterface
    {
        return $reverseContainer->getService($this->pool);
    }
    public function findCallback(\_PhpScoper17db12703726\Symfony\Component\DependencyInjection\ReverseContainer $reverseContainer) : callable
    {
        if (\is_string($callback = $this->callback)) {
            return '@' === $callback[0] ? $reverseContainer->getService(\substr($callback, 1)) : $callback;
        }
        if ('@' === $callback[0][0]) {
            $callback[0] = $reverseContainer->getService(\substr($callback[0], 1));
        }
        return $callback;
    }
    private function __construct(\_PhpScoper17db12703726\Symfony\Component\Cache\CacheItem $item, string $pool, $callback)
    {
        $this->item = $item;
        $this->pool = $pool;
        $this->callback = $callback;
    }
}