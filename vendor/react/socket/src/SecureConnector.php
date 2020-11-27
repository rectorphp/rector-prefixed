<?php

namespace _PhpScoper88fe6e0ad041\React\Socket;

use _PhpScoper88fe6e0ad041\React\EventLoop\LoopInterface;
use _PhpScoper88fe6e0ad041\React\Promise;
use BadMethodCallException;
use InvalidArgumentException;
use UnexpectedValueException;
final class SecureConnector implements \_PhpScoper88fe6e0ad041\React\Socket\ConnectorInterface
{
    private $connector;
    private $streamEncryption;
    private $context;
    public function __construct(\_PhpScoper88fe6e0ad041\React\Socket\ConnectorInterface $connector, \_PhpScoper88fe6e0ad041\React\EventLoop\LoopInterface $loop, array $context = array())
    {
        $this->connector = $connector;
        $this->streamEncryption = new \_PhpScoper88fe6e0ad041\React\Socket\StreamEncryption($loop, \false);
        $this->context = $context;
    }
    public function connect($uri)
    {
        if (!\function_exists('stream_socket_enable_crypto')) {
            return \_PhpScoper88fe6e0ad041\React\Promise\reject(new \BadMethodCallException('Encryption not supported on your platform (HHVM < 3.8?)'));
            // @codeCoverageIgnore
        }
        if (\strpos($uri, '://') === \false) {
            $uri = 'tls://' . $uri;
        }
        $parts = \parse_url($uri);
        if (!$parts || !isset($parts['scheme']) || $parts['scheme'] !== 'tls') {
            return \_PhpScoper88fe6e0ad041\React\Promise\reject(new \InvalidArgumentException('Given URI "' . $uri . '" is invalid'));
        }
        $uri = \str_replace('tls://', '', $uri);
        $context = $this->context;
        $encryption = $this->streamEncryption;
        $connected = \false;
        $promise = $this->connector->connect($uri)->then(function (\_PhpScoper88fe6e0ad041\React\Socket\ConnectionInterface $connection) use($context, $encryption, $uri, &$promise, &$connected) {
            // (unencrypted) TCP/IP connection succeeded
            $connected = \true;
            if (!$connection instanceof \_PhpScoper88fe6e0ad041\React\Socket\Connection) {
                $connection->close();
                throw new \UnexpectedValueException('Base connector does not use internal Connection class exposing stream resource');
            }
            // set required SSL/TLS context options
            foreach ($context as $name => $value) {
                \stream_context_set_option($connection->stream, 'ssl', $name, $value);
            }
            // try to enable encryption
            return $promise = $encryption->enable($connection)->then(null, function ($error) use($connection, $uri) {
                // establishing encryption failed => close invalid connection and return error
                $connection->close();
                throw new \RuntimeException('Connection to ' . $uri . ' failed during TLS handshake: ' . $error->getMessage(), $error->getCode());
            });
        });
        return new \_PhpScoper88fe6e0ad041\React\Promise\Promise(function ($resolve, $reject) use($promise) {
            $promise->then($resolve, $reject);
        }, function ($_, $reject) use(&$promise, $uri, &$connected) {
            if ($connected) {
                $reject(new \RuntimeException('Connection to ' . $uri . ' cancelled during TLS handshake'));
            }
            $promise->cancel();
            $promise = null;
        });
    }
}
