<?php

namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Socket;

use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\EventLoop\LoopInterface;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Promise;
use InvalidArgumentException;
use RuntimeException;
/**
 * Unix domain socket connector
 *
 * Unix domain sockets use atomic operations, so we can as well emulate
 * async behavior.
 */
final class UnixConnector implements \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Socket\ConnectorInterface
{
    private $loop;
    public function __construct(\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\EventLoop\LoopInterface $loop)
    {
        $this->loop = $loop;
    }
    public function connect($path)
    {
        if (\strpos($path, '://') === \false) {
            $path = 'unix://' . $path;
        } elseif (\substr($path, 0, 7) !== 'unix://') {
            return \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Promise\reject(new \InvalidArgumentException('Given URI "' . $path . '" is invalid'));
        }
        $resource = @\stream_socket_client($path, $errno, $errstr, 1.0);
        if (!$resource) {
            return \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Promise\reject(new \RuntimeException('Unable to connect to unix domain socket "' . $path . '": ' . $errstr, $errno));
        }
        $connection = new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Socket\Connection($resource, $this->loop);
        $connection->unix = \true;
        return \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Promise\resolve($connection);
    }
}
