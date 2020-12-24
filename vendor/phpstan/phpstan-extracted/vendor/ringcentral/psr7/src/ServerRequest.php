<?php

namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\RingCentral\Psr7;

use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Psr\Http\Message\ServerRequestInterface;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\RingCentral\Psr7\Request;
/**
 * PSR-7 server-side request implementation.
 */
class ServerRequest extends \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\RingCentral\Psr7\Request implements \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Psr\Http\Message\ServerRequestInterface
{
    private $attributes = array();
    private $serverParams = array();
    private $fileParams = array();
    private $cookies = array();
    private $queryParams = array();
    private $parsedBody = null;
    /**
     * @param null|string $method HTTP method for the request.
     * @param null|string|UriInterface $uri URI for the request.
     * @param array $headers Headers for the message.
     * @param string|resource|StreamInterface $body Message body.
     * @param string $protocolVersion HTTP protocol version.
     * @param array $serverParams Server params of the request.
     *
     * @throws InvalidArgumentException for an invalid URI
     */
    public function __construct($method, $uri, array $headers = array(), $body = null, $protocolVersion = '1.1', $serverParams = array())
    {
        parent::__construct($method, $uri, $headers, $body, $protocolVersion);
        $this->serverParams = $serverParams;
    }
    public function getServerParams()
    {
        return $this->serverParams;
    }
    public function getCookieParams()
    {
        return $this->cookies;
    }
    public function withCookieParams(array $cookies)
    {
        $new = clone $this;
        $new->cookies = $cookies;
        return $new;
    }
    public function getQueryParams()
    {
        return $this->queryParams;
    }
    public function withQueryParams(array $query)
    {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }
    public function getUploadedFiles()
    {
        return $this->fileParams;
    }
    public function withUploadedFiles(array $uploadedFiles)
    {
        $new = clone $this;
        $new->fileParams = $uploadedFiles;
        return $new;
    }
    public function getParsedBody()
    {
        return $this->parsedBody;
    }
    public function withParsedBody($data)
    {
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }
    public function getAttributes()
    {
        return $this->attributes;
    }
    public function getAttribute($name, $default = null)
    {
        if (!\array_key_exists($name, $this->attributes)) {
            return $default;
        }
        return $this->attributes[$name];
    }
    public function withAttribute($name, $value)
    {
        $new = clone $this;
        $new->attributes[$name] = $value;
        return $new;
    }
    public function withoutAttribute($name)
    {
        $new = clone $this;
        unset($new->attributes[$name]);
        return $new;
    }
}
