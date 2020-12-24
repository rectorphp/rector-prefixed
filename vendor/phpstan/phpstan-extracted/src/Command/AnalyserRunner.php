<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PHPStan\Command;

use _PhpScoperb75b35f52b74\PHPStan\Analyser\Analyser;
use _PhpScoperb75b35f52b74\PHPStan\Analyser\AnalyserResult;
use _PhpScoperb75b35f52b74\PHPStan\Parallel\ParallelAnalyser;
use _PhpScoperb75b35f52b74\PHPStan\Parallel\Scheduler;
use _PhpScoperb75b35f52b74\PHPStan\Process\CpuCoreCounter;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputInterface;
class AnalyserRunner
{
    /** @var Scheduler */
    private $scheduler;
    /** @var Analyser */
    private $analyser;
    /** @var ParallelAnalyser */
    private $parallelAnalyser;
    /** @var CpuCoreCounter */
    private $cpuCoreCounter;
    public function __construct(\_PhpScoperb75b35f52b74\PHPStan\Parallel\Scheduler $scheduler, \_PhpScoperb75b35f52b74\PHPStan\Analyser\Analyser $analyser, \_PhpScoperb75b35f52b74\PHPStan\Parallel\ParallelAnalyser $parallelAnalyser, \_PhpScoperb75b35f52b74\PHPStan\Process\CpuCoreCounter $cpuCoreCounter)
    {
        $this->scheduler = $scheduler;
        $this->analyser = $analyser;
        $this->parallelAnalyser = $parallelAnalyser;
        $this->cpuCoreCounter = $cpuCoreCounter;
    }
    /**
     * @param string[] $files
     * @param string[] $allAnalysedFiles
     * @param \Closure|null $preFileCallback
     * @param \Closure|null $postFileCallback
     * @param bool $debug
     * @param bool $allowParallel
     * @param string|null $projectConfigFile
     * @param string|null $tmpFile
     * @param string|null $insteadOfFile
     * @param InputInterface $input
     * @return AnalyserResult
     * @throws \Throwable
     */
    public function runAnalyser(array $files, array $allAnalysedFiles, ?\Closure $preFileCallback, ?\Closure $postFileCallback, bool $debug, bool $allowParallel, ?string $projectConfigFile, ?string $tmpFile, ?string $insteadOfFile, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputInterface $input) : \_PhpScoperb75b35f52b74\PHPStan\Analyser\AnalyserResult
    {
        $filesCount = \count($files);
        if ($filesCount === 0) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Analyser\AnalyserResult([], [], [], [], \false);
        }
        $schedule = $this->scheduler->scheduleWork($this->cpuCoreCounter->getNumberOfCpuCores(), $files);
        $mainScript = null;
        if (isset($_SERVER['argv'][0]) && \file_exists($_SERVER['argv'][0])) {
            $mainScript = $_SERVER['argv'][0];
        }
        if (!$debug && $allowParallel && $mainScript !== null && $schedule->getNumberOfProcesses() > 1) {
            return $this->parallelAnalyser->analyse($schedule, $mainScript, $postFileCallback, $projectConfigFile, $tmpFile, $insteadOfFile, $input);
        }
        return $this->analyser->analyse($this->switchTmpFile($files, $insteadOfFile, $tmpFile), $preFileCallback, $postFileCallback, $debug, $this->switchTmpFile($allAnalysedFiles, $insteadOfFile, $tmpFile));
    }
    /**
     * @param string[] $analysedFiles
     * @param string|null $insteadOfFile
     * @param string|null $tmpFile
     * @return string[]
     */
    private function switchTmpFile(array $analysedFiles, ?string $insteadOfFile, ?string $tmpFile) : array
    {
        $analysedFiles = \array_values(\array_filter($analysedFiles, static function (string $file) use($insteadOfFile) : bool {
            if ($insteadOfFile === null) {
                return \true;
            }
            return $file !== $insteadOfFile;
        }));
        if ($tmpFile !== null) {
            $analysedFiles[] = $tmpFile;
        }
        return $analysedFiles;
    }
}
