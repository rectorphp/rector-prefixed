<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix2020DecSat\Symfony\Component\HttpKernel;

use RectorPrefix2020DecSat\Symfony\Component\HttpClient\HttpClient;
use RectorPrefix2020DecSat\Symfony\Component\HttpFoundation\Request;
use RectorPrefix2020DecSat\Symfony\Component\HttpFoundation\Response;
use RectorPrefix2020DecSat\Symfony\Component\HttpFoundation\ResponseHeaderBag;
use RectorPrefix2020DecSat\Symfony\Component\Mime\Part\AbstractPart;
use RectorPrefix2020DecSat\Symfony\Component\Mime\Part\DataPart;
use RectorPrefix2020DecSat\Symfony\Component\Mime\Part\Multipart\FormDataPart;
use RectorPrefix2020DecSat\Symfony\Component\Mime\Part\TextPart;
use RectorPrefix2020DecSat\Symfony\Contracts\HttpClient\HttpClientInterface;
// Help opcache.preload discover always-needed symbols
\class_exists(\RectorPrefix2020DecSat\Symfony\Component\HttpFoundation\ResponseHeaderBag::class);
/**
 * An implementation of a Symfony HTTP kernel using a "real" HTTP client.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class HttpClientKernel implements \RectorPrefix2020DecSat\Symfony\Component\HttpKernel\HttpKernelInterface
{
    private $client;
    public function __construct(\RectorPrefix2020DecSat\Symfony\Contracts\HttpClient\HttpClientInterface $client = null)
    {
        if (null === $client && !\class_exists(\RectorPrefix2020DecSat\Symfony\Component\HttpClient\HttpClient::class)) {
            throw new \LogicException(\sprintf('You cannot use "%s" as the HttpClient component is not installed. Try running "composer require symfony/http-client".', __CLASS__));
        }
        $this->client = $client ?? \RectorPrefix2020DecSat\Symfony\Component\HttpClient\HttpClient::create();
    }
    public function handle(\RectorPrefix2020DecSat\Symfony\Component\HttpFoundation\Request $request, int $type = \RectorPrefix2020DecSat\Symfony\Component\HttpKernel\HttpKernelInterface::MASTER_REQUEST, bool $catch = \true) : \RectorPrefix2020DecSat\Symfony\Component\HttpFoundation\Response
    {
        $headers = $this->getHeaders($request);
        $body = '';
        if (null !== ($part = $this->getBody($request))) {
            $headers = \array_merge($headers, $part->getPreparedHeaders()->toArray());
            $body = $part->bodyToIterable();
        }
        $response = $this->client->request($request->getMethod(), $request->getUri(), ['headers' => $headers, 'body' => $body] + $request->attributes->get('http_client_options', []));
        $response = new \RectorPrefix2020DecSat\Symfony\Component\HttpFoundation\Response($response->getContent(!$catch), $response->getStatusCode(), $response->getHeaders(!$catch));
        $response->headers->remove('X-Body-File');
        $response->headers->remove('X-Body-Eval');
        $response->headers->remove('X-Content-Digest');
        $response->headers = new class($response->headers->all()) extends \RectorPrefix2020DecSat\Symfony\Component\HttpFoundation\ResponseHeaderBag
        {
            protected function computeCacheControlValue() : string
            {
                return $this->getCacheControlHeader();
                // preserve the original value
            }
        };
        return $response;
    }
    private function getBody(\RectorPrefix2020DecSat\Symfony\Component\HttpFoundation\Request $request) : ?\RectorPrefix2020DecSat\Symfony\Component\Mime\Part\AbstractPart
    {
        if (\in_array($request->getMethod(), ['GET', 'HEAD'])) {
            return null;
        }
        if (!\class_exists(\RectorPrefix2020DecSat\Symfony\Component\Mime\Part\AbstractPart::class)) {
            throw new \LogicException('You cannot pass non-empty bodies as the Mime component is not installed. Try running "composer require symfony/mime".');
        }
        if ($content = $request->getContent()) {
            return new \RectorPrefix2020DecSat\Symfony\Component\Mime\Part\TextPart($content, 'utf-8', 'plain', '8bit');
        }
        $fields = $request->request->all();
        foreach ($request->files->all() as $name => $file) {
            $fields[$name] = \RectorPrefix2020DecSat\Symfony\Component\Mime\Part\DataPart::fromPath($file->getPathname(), $file->getClientOriginalName(), $file->getClientMimeType());
        }
        return new \RectorPrefix2020DecSat\Symfony\Component\Mime\Part\Multipart\FormDataPart($fields);
    }
    private function getHeaders(\RectorPrefix2020DecSat\Symfony\Component\HttpFoundation\Request $request) : array
    {
        $headers = [];
        foreach ($request->headers as $key => $value) {
            $headers[$key] = $value;
        }
        $cookies = [];
        foreach ($request->cookies->all() as $name => $value) {
            $cookies[] = $name . '=' . $value;
        }
        if ($cookies) {
            $headers['cookie'] = \implode('; ', $cookies);
        }
        return $headers;
    }
}
