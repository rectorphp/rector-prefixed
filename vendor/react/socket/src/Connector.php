<?php

namespace _PhpScoperbd5d0c5f7638\React\Socket;

use _PhpScoperbd5d0c5f7638\React\Dns\Config\Config as DnsConfig;
use _PhpScoperbd5d0c5f7638\React\Dns\Resolver\Factory as DnsFactory;
use _PhpScoperbd5d0c5f7638\React\Dns\Resolver\ResolverInterface;
use _PhpScoperbd5d0c5f7638\React\EventLoop\LoopInterface;
/**
 * The `Connector` class is the main class in this package that implements the
 * `ConnectorInterface` and allows you to create streaming connections.
 *
 * You can use this connector to create any kind of streaming connections, such
 * as plaintext TCP/IP, secure TLS or local Unix connection streams.
 *
 * Under the hood, the `Connector` is implemented as a *higher-level facade*
 * or the lower-level connectors implemented in this package. This means it
 * also shares all of their features and implementation details.
 * If you want to typehint in your higher-level protocol implementation, you SHOULD
 * use the generic [`ConnectorInterface`](#connectorinterface) instead.
 *
 * @see ConnectorInterface for the base interface
 */
final class Connector implements \_PhpScoperbd5d0c5f7638\React\Socket\ConnectorInterface
{
    private $connectors = array();
    public function __construct(\_PhpScoperbd5d0c5f7638\React\EventLoop\LoopInterface $loop, array $options = array())
    {
        // apply default options if not explicitly given
        $options += array('tcp' => \true, 'tls' => \true, 'unix' => \true, 'dns' => \true, 'timeout' => \true, 'happy_eyeballs' => \true);
        if ($options['timeout'] === \true) {
            $options['timeout'] = (float) \ini_get("default_socket_timeout");
        }
        if ($options['tcp'] instanceof \_PhpScoperbd5d0c5f7638\React\Socket\ConnectorInterface) {
            $tcp = $options['tcp'];
        } else {
            $tcp = new \_PhpScoperbd5d0c5f7638\React\Socket\TcpConnector($loop, \is_array($options['tcp']) ? $options['tcp'] : array());
        }
        if ($options['dns'] !== \false) {
            if ($options['dns'] instanceof \_PhpScoperbd5d0c5f7638\React\Dns\Resolver\ResolverInterface) {
                $resolver = $options['dns'];
            } else {
                if ($options['dns'] !== \true) {
                    $server = $options['dns'];
                } else {
                    // try to load nameservers from system config or default to Google's public DNS
                    $config = \_PhpScoperbd5d0c5f7638\React\Dns\Config\Config::loadSystemConfigBlocking();
                    $server = $config->nameservers ? \reset($config->nameservers) : '8.8.8.8';
                }
                $factory = new \_PhpScoperbd5d0c5f7638\React\Dns\Resolver\Factory();
                $resolver = $factory->createCached($server, $loop);
            }
            if ($options['happy_eyeballs'] === \true) {
                $tcp = new \_PhpScoperbd5d0c5f7638\React\Socket\HappyEyeBallsConnector($loop, $tcp, $resolver);
            } else {
                $tcp = new \_PhpScoperbd5d0c5f7638\React\Socket\DnsConnector($tcp, $resolver);
            }
        }
        if ($options['tcp'] !== \false) {
            $options['tcp'] = $tcp;
            if ($options['timeout'] !== \false) {
                $options['tcp'] = new \_PhpScoperbd5d0c5f7638\React\Socket\TimeoutConnector($options['tcp'], $options['timeout'], $loop);
            }
            $this->connectors['tcp'] = $options['tcp'];
        }
        if ($options['tls'] !== \false) {
            if (!$options['tls'] instanceof \_PhpScoperbd5d0c5f7638\React\Socket\ConnectorInterface) {
                $options['tls'] = new \_PhpScoperbd5d0c5f7638\React\Socket\SecureConnector($tcp, $loop, \is_array($options['tls']) ? $options['tls'] : array());
            }
            if ($options['timeout'] !== \false) {
                $options['tls'] = new \_PhpScoperbd5d0c5f7638\React\Socket\TimeoutConnector($options['tls'], $options['timeout'], $loop);
            }
            $this->connectors['tls'] = $options['tls'];
        }
        if ($options['unix'] !== \false) {
            if (!$options['unix'] instanceof \_PhpScoperbd5d0c5f7638\React\Socket\ConnectorInterface) {
                $options['unix'] = new \_PhpScoperbd5d0c5f7638\React\Socket\UnixConnector($loop);
            }
            $this->connectors['unix'] = $options['unix'];
        }
    }
    public function connect($uri)
    {
        $scheme = 'tcp';
        if (\strpos($uri, '://') !== \false) {
            $scheme = (string) \substr($uri, 0, \strpos($uri, '://'));
        }
        if (!isset($this->connectors[$scheme])) {
            return \_PhpScoperbd5d0c5f7638\React\Promise\reject(new \RuntimeException('No connector available for URI scheme "' . $scheme . '"'));
        }
        return $this->connectors[$scheme]->connect($uri);
    }
}
