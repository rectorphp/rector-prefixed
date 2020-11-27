<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace _PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\Event;

use _PhpScoperbd5d0c5f7638\Symfony\Component\HttpFoundation\Response;
/**
 * @deprecated since Symfony 4.3, use RequestEvent instead
 */
class GetResponseEvent extends \_PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\Event\KernelEvent
{
    private $response;
    /**
     * Returns the response object.
     *
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }
    /**
     * Sets a response and stops event propagation.
     */
    public function setResponse(\_PhpScoperbd5d0c5f7638\Symfony\Component\HttpFoundation\Response $response)
    {
        $this->response = $response;
        $this->stopPropagation();
    }
    /**
     * Returns whether a response was set.
     *
     * @return bool Whether a response was set
     */
    public function hasResponse()
    {
        return null !== $this->response;
    }
}