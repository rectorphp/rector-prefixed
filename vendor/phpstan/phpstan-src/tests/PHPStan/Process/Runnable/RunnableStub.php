<?php

declare (strict_types=1);
namespace PHPStan\Process\Runnable;

use _PhpScoperbd5d0c5f7638\React\Promise\CancellablePromiseInterface;
use _PhpScoperbd5d0c5f7638\React\Promise\Deferred;
class RunnableStub implements \PHPStan\Process\Runnable\Runnable
{
    /** @var string */
    private $name;
    /** @var Deferred */
    private $deferred;
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->deferred = new \_PhpScoperbd5d0c5f7638\React\Promise\Deferred();
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function finish() : void
    {
        $this->deferred->resolve();
    }
    public function run() : \_PhpScoperbd5d0c5f7638\React\Promise\CancellablePromiseInterface
    {
        /** @var CancellablePromiseInterface */
        return $this->deferred->promise();
    }
    public function cancel() : void
    {
        $this->deferred->reject(new \PHPStan\Process\Runnable\RunnableCanceledException(\sprintf('Runnable %s canceled', $this->getName())));
    }
}