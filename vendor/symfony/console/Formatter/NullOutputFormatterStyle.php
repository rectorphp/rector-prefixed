<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace _PhpScoper50d83356d739\Symfony\Component\Console\Formatter;

/**
 * @author Tien Xuan Vo <tien.xuan.vo@gmail.com>
 */
final class NullOutputFormatterStyle implements \_PhpScoper50d83356d739\Symfony\Component\Console\Formatter\OutputFormatterStyleInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(string $text) : string
    {
        return $text;
    }
    /**
     * {@inheritdoc}
     */
    public function setBackground(string $color = null) : void
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function setForeground(string $color = null) : void
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function setOption(string $option) : void
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options) : void
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function unsetOption(string $option) : void
    {
        // do nothing
    }
}
