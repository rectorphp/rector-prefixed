<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20201228\Symfony\Component\Cache\Adapter;

use RectorPrefix20201228\Symfony\Contracts\Cache\TagAwareCacheInterface;
/**
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class TraceableTagAwareAdapter extends \RectorPrefix20201228\Symfony\Component\Cache\Adapter\TraceableAdapter implements \RectorPrefix20201228\Symfony\Component\Cache\Adapter\TagAwareAdapterInterface, \RectorPrefix20201228\Symfony\Contracts\Cache\TagAwareCacheInterface
{
    public function __construct(\RectorPrefix20201228\Symfony\Component\Cache\Adapter\TagAwareAdapterInterface $pool)
    {
        parent::__construct($pool);
    }
    /**
     * {@inheritdoc}
     */
    public function invalidateTags(array $tags)
    {
        $event = $this->start(__FUNCTION__);
        try {
            return $event->result = $this->pool->invalidateTags($tags);
        } finally {
            $event->end = \microtime(\true);
        }
    }
}
