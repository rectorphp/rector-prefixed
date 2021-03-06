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
class ServiceUnavailableHttpException extends \RectorPrefix20210317\Symfony\Component\HttpKernel\Exception\HttpException
{
    /**
     * @param int|string|null $retryAfter The number of seconds or HTTP-date after which the request may be retried
     * @param string|null     $message    The internal exception message
     * @param \Throwable $previous   The previous exception
     * @param int|null        $code       The internal exception code
     * @param mixed[] $headers
     */
    public function __construct($retryAfter = null, $message = '', $previous = null, $code = 0, $headers = [])
    {
        if ($retryAfter) {
            $headers['Retry-After'] = $retryAfter;
        }
        parent::__construct(503, $message, $previous, $headers, $code);
    }
}
