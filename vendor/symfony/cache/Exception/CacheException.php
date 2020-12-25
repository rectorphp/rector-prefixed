<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace _PhpScoper267b3276efc2\Symfony\Component\Cache\Exception;

use _PhpScoper267b3276efc2\Psr\Cache\CacheException as Psr6CacheInterface;
use _PhpScoper267b3276efc2\Psr\SimpleCache\CacheException as SimpleCacheInterface;
if (\interface_exists(\_PhpScoper267b3276efc2\Psr\SimpleCache\CacheException::class)) {
    class CacheException extends \Exception implements \_PhpScoper267b3276efc2\Psr\Cache\CacheException, \_PhpScoper267b3276efc2\Psr\SimpleCache\CacheException
    {
    }
} else {
    class CacheException extends \Exception implements \_PhpScoper267b3276efc2\Psr\Cache\CacheException
    {
    }
}
