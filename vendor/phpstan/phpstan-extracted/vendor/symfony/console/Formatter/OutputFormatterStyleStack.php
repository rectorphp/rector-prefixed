<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace _HumbugBox221ad6f1b81f\Symfony\Component\Console\Formatter;

use _HumbugBox221ad6f1b81f\Symfony\Component\Console\Exception\InvalidArgumentException;
use _HumbugBox221ad6f1b81f\Symfony\Contracts\Service\ResetInterface;
/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class OutputFormatterStyleStack implements \_HumbugBox221ad6f1b81f\Symfony\Contracts\Service\ResetInterface
{
    /**
     * @var OutputFormatterStyleInterface[]
     */
    private $styles;
    private $emptyStyle;
    public function __construct(\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Formatter\OutputFormatterStyleInterface $emptyStyle = null)
    {
        $this->emptyStyle = $emptyStyle ?: new \_HumbugBox221ad6f1b81f\Symfony\Component\Console\Formatter\OutputFormatterStyle();
        $this->reset();
    }
    /**
     * Resets stack (ie. empty internal arrays).
     */
    public function reset()
    {
        $this->styles = [];
    }
    /**
     * Pushes a style in the stack.
     */
    public function push(\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Formatter\OutputFormatterStyleInterface $style)
    {
        $this->styles[] = $style;
    }
    /**
     * Pops a style from the stack.
     *
     * @return OutputFormatterStyleInterface
     *
     * @throws InvalidArgumentException When style tags incorrectly nested
     */
    public function pop(\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Formatter\OutputFormatterStyleInterface $style = null)
    {
        if (empty($this->styles)) {
            return $this->emptyStyle;
        }
        if (null === $style) {
            return \array_pop($this->styles);
        }
        foreach (\array_reverse($this->styles, \true) as $index => $stackedStyle) {
            if ($style->apply('') === $stackedStyle->apply('')) {
                $this->styles = \array_slice($this->styles, 0, $index);
                return $stackedStyle;
            }
        }
        throw new \_HumbugBox221ad6f1b81f\Symfony\Component\Console\Exception\InvalidArgumentException('Incorrectly nested style tag found.');
    }
    /**
     * Computes current style with stacks top codes.
     *
     * @return OutputFormatterStyle
     */
    public function getCurrent()
    {
        if (empty($this->styles)) {
            return $this->emptyStyle;
        }
        return $this->styles[\count($this->styles) - 1];
    }
    /**
     * @return $this
     */
    public function setEmptyStyle(\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Formatter\OutputFormatterStyleInterface $emptyStyle)
    {
        $this->emptyStyle = $emptyStyle;
        return $this;
    }
    /**
     * @return OutputFormatterStyleInterface
     */
    public function getEmptyStyle()
    {
        return $this->emptyStyle;
    }
}