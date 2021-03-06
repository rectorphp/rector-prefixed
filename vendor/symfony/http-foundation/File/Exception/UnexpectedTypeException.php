<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210317\Symfony\Component\HttpFoundation\File\Exception;

class UnexpectedTypeException extends \RectorPrefix20210317\Symfony\Component\HttpFoundation\File\Exception\FileException
{
    /**
     * @param string $expectedType
     */
    public function __construct($value, $expectedType)
    {
        parent::__construct(\sprintf('Expected argument of type %s, %s given', $expectedType, \get_debug_type($value)));
    }
}
