<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PHPStan\Command;

use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Clue\React\NDJson\Decoder;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Clue\React\NDJson\Encoder;
use _PhpScoperb75b35f52b74\PHPStan\Analyser\FileAnalyser;
use _PhpScoperb75b35f52b74\PHPStan\Analyser\NodeScopeResolver;
use _PhpScoperb75b35f52b74\PHPStan\DependencyInjection\Container;
use _PhpScoperb75b35f52b74\PHPStan\Rules\Registry;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\EventLoop\StreamSelectLoop;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Socket\ConnectionInterface;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Socket\TcpConnector;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Stream\ReadableStreamInterface;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Stream\WritableStreamInterface;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Command\Command;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputArgument;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputInterface;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Output\OutputInterface;
class WorkerCommand extends \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Command\Command
{
    private const NAME = 'worker';
    /** @var string[] */
    private $composerAutoloaderProjectPaths;
    /** @var int */
    private $errorCount = 0;
    /**
     * @param string[] $composerAutoloaderProjectPaths
     */
    public function __construct(array $composerAutoloaderProjectPaths)
    {
        parent::__construct();
        $this->composerAutoloaderProjectPaths = $composerAutoloaderProjectPaths;
    }
    protected function configure() : void
    {
        $this->setName(self::NAME)->setDescription('(Internal) Support for parallel analysis.')->setDefinition([new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputArgument('paths', \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputArgument::OPTIONAL | \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputArgument::IS_ARRAY, 'Paths with source code to run analysis on'), new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption('paths-file', null, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Path to a file with a list of paths to run analysis on'), new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption('configuration', 'c', \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Path to project configuration file'), new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption(\_PhpScoperb75b35f52b74\PHPStan\Command\AnalyseCommand::OPTION_LEVEL, 'l', \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Level of rule options - the higher the stricter'), new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption('autoload-file', 'a', \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Project\'s additional autoload file path'), new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption('memory-limit', null, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Memory limit for analysis'), new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption('xdebug', null, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Allow running with XDebug for debugging purposes'), new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption('port', null, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED), new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption('identifier', null, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED), new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption('tmp-file', null, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED), new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption('instead-of', null, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED)]);
    }
    protected function execute(\_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\InputInterface $input, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $paths = $input->getArgument('paths');
        $memoryLimit = $input->getOption('memory-limit');
        $autoloadFile = $input->getOption('autoload-file');
        $configuration = $input->getOption('configuration');
        $level = $input->getOption(\_PhpScoperb75b35f52b74\PHPStan\Command\AnalyseCommand::OPTION_LEVEL);
        $pathsFile = $input->getOption('paths-file');
        $allowXdebug = $input->getOption('xdebug');
        $port = $input->getOption('port');
        $identifier = $input->getOption('identifier');
        if (!\is_array($paths) || !\is_string($memoryLimit) && $memoryLimit !== null || !\is_string($autoloadFile) && $autoloadFile !== null || !\is_string($configuration) && $configuration !== null || !\is_string($level) && $level !== null || !\is_string($pathsFile) && $pathsFile !== null || !\is_bool($allowXdebug) || !\is_string($port) || !\is_string($identifier)) {
            throw new \_PhpScoperb75b35f52b74\PHPStan\ShouldNotHappenException();
        }
        /** @var string|null $tmpFile */
        $tmpFile = $input->getOption('tmp-file');
        /** @var string|null $insteadOfFile */
        $insteadOfFile = $input->getOption('instead-of');
        $singleReflectionFile = null;
        if ($tmpFile !== null) {
            $singleReflectionFile = $tmpFile;
        }
        try {
            $inceptionResult = \_PhpScoperb75b35f52b74\PHPStan\Command\CommandHelper::begin($input, $output, $paths, $pathsFile, $memoryLimit, $autoloadFile, $this->composerAutoloaderProjectPaths, $configuration, null, $level, $allowXdebug, \false, \false, $singleReflectionFile);
        } catch (\_PhpScoperb75b35f52b74\PHPStan\Command\InceptionNotSuccessfulException $e) {
            return 1;
        }
        $loop = new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\EventLoop\StreamSelectLoop();
        $container = $inceptionResult->getContainer();
        try {
            [$analysedFiles] = $inceptionResult->getFiles();
            $analysedFiles = $this->switchTmpFile($analysedFiles, $insteadOfFile, $tmpFile);
        } catch (\_PhpScoperb75b35f52b74\PHPStan\File\PathNotFoundException $e) {
            $inceptionResult->getErrorOutput()->writeLineFormatted(\sprintf('<error>%s</error>', $e->getMessage()));
            return 1;
        }
        /** @var NodeScopeResolver $nodeScopeResolver */
        $nodeScopeResolver = $container->getByType(\_PhpScoperb75b35f52b74\PHPStan\Analyser\NodeScopeResolver::class);
        $nodeScopeResolver->setAnalysedFiles($analysedFiles);
        $analysedFiles = \array_fill_keys($analysedFiles, \true);
        $tcpConector = new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Socket\TcpConnector($loop);
        $tcpConector->connect(\sprintf('127.0.0.1:%d', $port))->done(function (\_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Socket\ConnectionInterface $connection) use($container, $identifier, $output, $analysedFiles, $tmpFile, $insteadOfFile) : void {
            $out = new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Clue\React\NDJson\Encoder($connection, \defined('JSON_INVALID_UTF8_IGNORE') ? \JSON_INVALID_UTF8_IGNORE : 0);
            $in = new \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Clue\React\NDJson\Decoder($connection, \true, 512, \defined('JSON_INVALID_UTF8_IGNORE') ? \JSON_INVALID_UTF8_IGNORE : 0, $container->getParameter('parallel')['buffer']);
            $out->write(['action' => 'hello', 'identifier' => $identifier]);
            $this->runWorker($container, $out, $in, $output, $analysedFiles, $tmpFile, $insteadOfFile);
        });
        $loop->run();
        if ($this->errorCount > 0) {
            return 1;
        }
        return 0;
    }
    /**
     * @param Container $container
     * @param WritableStreamInterface $out
     * @param ReadableStreamInterface $in
     * @param OutputInterface $output
     * @param array<string, true> $analysedFiles
     * @param string|null $tmpFile
     * @param string|null $insteadOfFile
     */
    private function runWorker(\_PhpScoperb75b35f52b74\PHPStan\DependencyInjection\Container $container, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Stream\WritableStreamInterface $out, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\React\Stream\ReadableStreamInterface $in, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Output\OutputInterface $output, array $analysedFiles, ?string $tmpFile, ?string $insteadOfFile) : void
    {
        $handleError = function (\Throwable $error) use($out, $output) : void {
            $this->errorCount++;
            $output->writeln(\sprintf('Error: %s', $error->getMessage()));
            $out->write(['action' => 'result', 'result' => ['errors' => [$error->getMessage()], 'dependencies' => [], 'filesCount' => 0, 'internalErrorsCount' => 1]]);
            $out->end();
        };
        $out->on('error', $handleError);
        /** @var FileAnalyser $fileAnalyser */
        $fileAnalyser = $container->getByType(\_PhpScoperb75b35f52b74\PHPStan\Analyser\FileAnalyser::class);
        /** @var Registry $registry */
        $registry = $container->getByType(\_PhpScoperb75b35f52b74\PHPStan\Rules\Registry::class);
        $in->on('data', function (array $json) use($fileAnalyser, $registry, $out, $analysedFiles, $tmpFile, $insteadOfFile) : void {
            $action = $json['action'];
            if ($action !== 'analyse') {
                return;
            }
            $internalErrorsCount = 0;
            $files = $json['files'];
            $errors = [];
            $dependencies = [];
            $exportedNodes = [];
            foreach ($files as $file) {
                try {
                    if ($file === $insteadOfFile) {
                        $file = $tmpFile;
                    }
                    $fileAnalyserResult = $fileAnalyser->analyseFile($file, $analysedFiles, $registry, null);
                    $fileErrors = $fileAnalyserResult->getErrors();
                    $dependencies[$file] = $fileAnalyserResult->getDependencies();
                    $exportedNodes[$file] = $fileAnalyserResult->getExportedNodes();
                    foreach ($fileErrors as $fileError) {
                        $errors[] = $fileError;
                    }
                } catch (\Throwable $t) {
                    $this->errorCount++;
                    $internalErrorsCount++;
                    $internalErrorMessage = \sprintf('Internal error: %s in file %s', $t->getMessage(), $file);
                    $internalErrorMessage .= \sprintf('%sRun PHPStan with --debug option and post the stack trace to:%s%s', "\n", "\n", 'https://github.com/phpstan/phpstan/issues/new');
                    $errors[] = $internalErrorMessage;
                }
            }
            $out->write(['action' => 'result', 'result' => ['errors' => $errors, 'dependencies' => $dependencies, 'exportedNodes' => $exportedNodes, 'filesCount' => \count($files), 'internalErrorsCount' => $internalErrorsCount]]);
        });
        $in->on('error', $handleError);
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
