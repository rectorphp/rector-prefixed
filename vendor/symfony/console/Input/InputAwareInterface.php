<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Input;

/**
 * InputAwareInterface should be implemented by classes that depends on the
 * Console Input.
 *
 * @author Wouter J <waldio.webdesign@gmail.com>
 */
interface InputAwareInterface
{
    /**
     * Sets the Console Input.
     */
    public function setInput(\_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Input\InputInterface $input);
}