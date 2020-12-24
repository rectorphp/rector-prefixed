<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PHPStan\Process;

use _PhpScoperb75b35f52b74\PHPStan\Process\Runnable\Runnable;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\ChildProcess\Process;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\EventLoop\LoopInterface;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Promise\CancellablePromiseInterface;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Promise\Deferred;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Promise\ExtendedPromiseInterface;
class ProcessPromise implements \_PhpScoperb75b35f52b74\PHPStan\Process\Runnable\Runnable
{
    /** @var LoopInterface */
    private $loop;
    /** @var string */
    private $name;
    /** @var string */
    private $command;
    /** @var Deferred */
    private $deferred;
    /** @var Process|null */
    private $process = null;
    /** @var bool */
    private $canceled = \false;
    public function __construct(\_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\EventLoop\LoopInterface $loop, string $name, string $command)
    {
        $this->loop = $loop;
        $this->name = $name;
        $this->command = $command;
        $this->deferred = new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Promise\Deferred();
    }
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * @return ExtendedPromiseInterface&CancellablePromiseInterface
     */
    public function run() : \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Promise\CancellablePromiseInterface
    {
        $tmpStdOutResource = \tmpfile();
        if ($tmpStdOutResource === \false) {
            throw new \_PhpScoperb75b35f52b74\PHPStan\ShouldNotHappenException('Failed creating temp file for stdout.');
        }
        $tmpStdErrResource = \tmpfile();
        if ($tmpStdErrResource === \false) {
            throw new \_PhpScoperb75b35f52b74\PHPStan\ShouldNotHappenException('Failed creating temp file for stderr.');
        }
        $this->process = new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\ChildProcess\Process($this->command, null, null, [1 => $tmpStdOutResource, 2 => $tmpStdErrResource]);
        $this->process->start($this->loop);
        $this->process->on('exit', function ($exitCode) use($tmpStdOutResource, $tmpStdErrResource) : void {
            if ($this->canceled) {
                \fclose($tmpStdOutResource);
                \fclose($tmpStdErrResource);
                return;
            }
            \rewind($tmpStdOutResource);
            $stdOut = \stream_get_contents($tmpStdOutResource);
            \fclose($tmpStdOutResource);
            \rewind($tmpStdErrResource);
            $stdErr = \stream_get_contents($tmpStdErrResource);
            \fclose($tmpStdErrResource);
            if ($exitCode === null) {
                $this->deferred->reject(new \_PhpScoperb75b35f52b74\PHPStan\Process\ProcessCrashedException($stdOut . $stdErr));
                return;
            }
            if ($exitCode === 0) {
                $this->deferred->resolve($stdOut);
                return;
            }
            $this->deferred->reject(new \_PhpScoperb75b35f52b74\PHPStan\Process\ProcessCrashedException($stdOut . $stdErr));
        });
        /** @var ExtendedPromiseInterface&CancellablePromiseInterface */
        return $this->deferred->promise();
    }
    public function cancel() : void
    {
        if ($this->process === null) {
            throw new \_PhpScoperb75b35f52b74\PHPStan\ShouldNotHappenException('Cancelling process before running');
        }
        $this->canceled = \true;
        $this->process->terminate();
        $this->deferred->reject(new \_PhpScoperb75b35f52b74\PHPStan\Process\ProcessCanceledException());
    }
}
