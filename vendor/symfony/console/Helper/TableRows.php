<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210317\Symfony\Component\Console\Helper;

/**
 * @internal
 */
class TableRows implements \IteratorAggregate
{
    private $generator;
    /**
     * @param callable $generator
     */
    public function __construct($generator)
    {
        $this->generator = $generator;
    }
    public function getIterator() : \Traversable
    {
        $g = $this->generator;
        return $g();
    }
}
