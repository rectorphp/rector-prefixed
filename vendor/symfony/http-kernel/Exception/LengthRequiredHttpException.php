<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210317\Symfony\Component\HttpKernel\Exception;

/**
 * @author Ben Ramsey <ben@benramsey.com>
 */
class LengthRequiredHttpException extends \RectorPrefix20210317\Symfony\Component\HttpKernel\Exception\HttpException
{
    /**
     * @param string|null     $message  The internal exception message
     * @param \Throwable $previous The previous exception
     * @param int             $code     The internal exception code
     * @param mixed[] $headers
     */
    public function __construct($message = '', $previous = null, $code = 0, $headers = [])
    {
        parent::__construct(411, $message, $previous, $headers, $code);
    }
}
